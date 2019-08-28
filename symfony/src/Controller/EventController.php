<?php


namespace App\Controller;

use App\Entity\Event;
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

     //   $myevents = ;

        $user = $this->getUser();

        $event = new Event();

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $event->setIdUser($user);
            $entityManager->persist($event);
            $entityManager->flush();

         //   return $this->redirectToRoute('user_edit');
        }
        return $this->render('event/event.html.twig', [
            'form' => $form->createView(),
         //   'events' => ,
            'user' => $user
        ]);
    }
}