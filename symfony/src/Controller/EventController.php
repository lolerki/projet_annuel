<?php


namespace App\Controller;

use App\Entity\Event;
use App\Entity\ParticipationEvent;
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

        $user = $this->getUser();
        $event = new Event();

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        $events = $this->getDoctrine()->getRepository(Event::class)->findBy(array('idUser' => $user));

        $myEvents = $this->getDoctrine()->getRepository(ParticipationEvent::class)->findBy(array('idUser' => $user));


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $event->setIdUser($user);
            $entityManager->persist($event);
            $entityManager->flush();

         //   return $this->redirectToRoute('user_edit');
        }
        return $this->render('event/event.html.twig', [
            'form' => $form->createView(),
            'events' => $events,
            'user' => $user,
            'myevents' => $myEvents
        ]);
    }


    /**
     * @Route("/event/{id}", name="event_show")
     */
    public function showAction($id): Response
    {
        $user = $this->getUser();

        $participation = false;

        $recherche = $this->getDoctrine()->getRepository(ParticipationEvent::class)->findOneBy(array('idEvent' => $id, 'idUser' => $user));

        if($recherche != null){
            $participation = true;
        }

        $event = $this->getDoctrine()->getRepository(Event::class)->findOneBy(array('id' => $id));

        return $this->render('event/show.html.twig', [
            'event' => $event,
            'participe' => $participation
        ]);
    }

    /**
     * @Route("/event/edit/{id}", name="event_edit")
     */
    public function editAction(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_show');
        }

        return $this->render('Article/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/event/participe/{id}", name="event_participe")
     */
    public function newAction($id): Response
    {

        $user = $this->getUser();

        $participer = new ParticipationEvent();
        $event = $this->getDoctrine()->getRepository(Event::class)->findOneBy(array('id' => $id));

        $entityManager = $this->getDoctrine()->getManager();
        $participer->setIdUser($user);
        $participer->setIdEvent($event);
        $entityManager->persist($participer);
        $entityManager->flush();

        $message = "<i class='far fa-check-circle'></i> Vous êtes maintenant inscrit à cette événement";

        return new Response(json_encode(array('message' => $message, 'result' => 'success')));
    }

}