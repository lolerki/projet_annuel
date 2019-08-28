<?php

namespace App\Controller;

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
     */
    public function indexAction(Request $request): Response
    {
        $user = $this->getUser();

        $profile = new Profile();

        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $profile->setIdUser($user);
            $entityManager->persist($profile);
            $entityManager->flush();

        }
        return $this->render('profile/new.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);

    }

    /**
     * @Route("/profile/show", name="profile_show")
     */
    public function showAction(Request $request): Response
    {

        return $this->render('profile/show.html.twig', [

        ]);

    }

}