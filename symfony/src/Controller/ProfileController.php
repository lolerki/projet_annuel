<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProfileType;

class ProfileController extends AbstractController
{

    /**
     * @Route("/profile/edit", name="profile_edit")
     */
    public function indexAction(Request $request): Response
    {
        $profile = $this->getUser();
        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //  $this->getDoctrine()->getManager()->flush();
            //  $this->addFlash('success', 'user.updated_successfully');

            //   return $this->redirectToRoute('user_edit');
        }
        return $this->render('profile/edit.html.twi', [
            'form' => $form->createView(),
        ]);

    }

}