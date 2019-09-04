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

class ProfileController extends AbstractController
{

    /**
     * @Route("/profile/new", name="profile_new")
     * @IsGranted("ROLE_USER")
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

            return $this->redirectToRoute('profile_show');

        }
        return $this->render('profile/new.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/profile/show", name="profile_show")
     * @IsGranted("ROLE_USER")
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
     * @Route("/dashboard/profile/edit/{id}", name="profile_edit")
     * @IsGranted("ROLE_USER")
     */
    public function editAction(Request $request, Profile $profile): Response
    {

        $form = $this->createForm(ProfileType::class, $profile);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($profile);
            $entityManager->flush();

              return $this->redirectToRoute('profile_show');
        }

        return $this->render('profile/edit.html.twig', [
            'profile' => $profile,
            'form' => $form->createView(),
        ]);

    }


}