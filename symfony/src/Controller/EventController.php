<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\EventType;


class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function indexAction(Request $request): Response
    {

        $form = $this->createForm(EventType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        //    $this->getDoctrine()->getManager()->flush();
        //    $this->addFlash('success', 'user.updated_successfully');

         //   return $this->redirectToRoute('user_edit');
        }
        return $this->render('event/event.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}