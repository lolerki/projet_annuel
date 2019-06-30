<?php
/**
 * Created by PhpStorm.
 * User: virgilmoreau
 * Date: 2019-03-25
 * Time: 14:21
 */

namespace App\Security;

use App\Entity\User;
use App\Entity\Profile;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FacebookAuthenticator extends SocialAuthenticator
{
    use TargetPathTrait;

    private $clientRegistry;
    private $em;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator)
    {
        $this->clientRegistry = $clientRegistry;
        $this->urlGenerator = $urlGenerator;
        $this->em = $em;
    }

    public function supports(Request $request)
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_facebook_check';
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getFacebookClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var FacebookUser $facebookUser */
        $facebookUser = $this->getFacebookClient()
            ->fetchUserFromToken($credentials);

        $email = $facebookUser->getEmail();

        // 1) have they logged in with Facebook before? Easy!
        $existingUser = $this->em->getRepository(User::class)
            ->findOneBy(['facebookId' => $facebookUser->getId()]);
        if ($existingUser) {
            return $existingUser;
        }

        // 2) do we have a matching user by email?
        $user = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        // 3) Maybe you just want to "register" them by creating
        // a User object
        $user = new User();
        $user->setEmail($email);
        $user->setFacebookId($facebookUser->getId());
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * @return FacebookClient
     */
    private function getFacebookClient()
    {
        return $this->clientRegistry
            // "facebook_main" is the key used in config/packages/knpu_oauth2_client.yaml
            ->getClient('facebook_main');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        // redirect to some "app_index" route - of wherever you want
        return new RedirectResponse($this->urlGenerator->generate('app_index'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            '/connect/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    // ...
}