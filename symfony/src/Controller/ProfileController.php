<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Profile;
use App\Form\ProfileType;

/**
 * Require ROLE_USER for *every* controller method in this class.
 *
 * @IsGranted("ROLE_USER")
 */
class ProfileController extends AbstractController
{

    /**
     * @Route("/profile/new", name="profile_new")
     */
    public function indexAction(Request $request): Response
    {

        $user = $this->getUser();
        $profile = new Profile();

        if($user->getProfile() != null){
            return $this->redirectToRoute('profile_edit', ['id' => $user->getProfile()->getId()]);
        }

        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $profile->setIdUser($user);
            $entityManager->persist($profile);
            $entityManager->flush();

        }
        return $this->render('profile/new.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/profile/show", name="profile_show")
     */
    public function showAction(Request $request): Response
    {

        $user = $this->getUser();

        if($user->getProfile() == null){
            return $this->redirectToRoute('profile_new');
        }

        $profile = $this->getUser()->getProfile();

        return $this->render('profile/show.html.twig', [
            'profile' => $profile
        ]);

    }

    /**
     * @Route("/profile/show/{id}", name="profile_view")
     */
    public function profileViewAction($id): Response
    {

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('id' => $id));

        $profile = $user->getProfile();

        if ($user->getProfile() == null) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('profile/show.html.twig', [
            'profile' => $profile
        ]);

    }

    /**
     * @Route("/myprofile/edit/{id}", name="profile_edit")
     */
    public function editAction(Request $request, Profile $profile): Response
    {

        $form = $this->createForm(ProfileType::class, $profile);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($profile);
            $entityManager->flush();

            //  return $this->redirectToRoute('app_article_show');
        }

        return $this->render('profile/edit.html.twig', [
            'profile' => $profile,
            'form' => $form->createView(),
        ]);

    }


}