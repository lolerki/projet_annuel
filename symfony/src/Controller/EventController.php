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
use App\Form\CommentType;
use App\Entity\Like;
use App\Entity\Comment;


class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     * @IsGranted("ROLE_USER")
     */
    public function indexAction(Request $request): Response
    {

        $user = $this->getUser();
        $event = new Event();

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        //EVENT créer par l'utilisateur
        $events = $this->getDoctrine()->getRepository(Event::class)->findBy(array('idUser' => $user, 'statut' => 1));

        //event participé
        $myEvents = $this->getDoctrine()->getRepository(ParticipationEvent::class)->findBy(array('idUser' => $user));

        //event like
        $likes = $this->getDoctrine()->getRepository(Like::class)->findBy(array('idUser' => $user));

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
            'likes' => $likes,
            'user' => $user,
            'myevents' => $myEvents
        ]);
    }


    /**
     * @Route("/event/{id}", name="event_show")
     */
    public function showAction(Request $request, $id): Response
    {

        $user = $this->getUser();
        $newComment = new Comment();
        $form = $this->createForm(CommentType::class, $newComment);
        $form->handleRequest($request);

        $inscription = true;
        $participation = false;
        $like = false;
        $finish = false;

        if ($user != null) {

            $myEvent = $this->getDoctrine()->getRepository(Event::class)->findEventByUser($user->getId(), $id);

            if ($myEvent != null) {
                $inscription = false;
            }

        }


        $event = $this->getDoctrine()->getRepository(Event::class)->findOneBy(array('id' => $id));

        //vérification si event termine
        if ($event->getDateEvent() <= new \DateTime('now')) {
            $finish = true;
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $newComment->setIdEvent($event);
            $newComment->setIdUser($user);
            $entityManager->persist($newComment);
            $entityManager->flush();

            //  return $this->redirectToRoute('app_article_show');
        }

        $recherche = $this->getDoctrine()->getRepository(ParticipationEvent::class)->findOneBy(array('idEvent' => $id, 'idUser' => $user));

        $rechercheLike = $this->getDoctrine()->getRepository(Like::class)->findOneBy(array('idEvent' => $id, 'idUser' => $user));

        if ($rechercheLike != null) {
            $like = true;
        }

        if ($recherche != null) {
            $participation = true;
        }

        $comment = $this->getDoctrine()->getRepository(Comment::class)->findBy(array('idEvent' => $event,));

        if (empty($comment)) {
            $comment = null;
        }

        return $this->render('event/show.html.twig', [
            'event' => $event,
            'comments' => $comment,
            'participe' => $participation,
            'inscrire' => $inscription,
            'finish' => $finish,
            'like' => $like,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/event/edit/{id}", name="event_edit")
     * @IsGranted("ROLE_USER")
     */
    public function editAction(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            //  return $this->redirectToRoute('app_article_show');
        }

        return $this->render('event/edit.html.twig', [
            'Event' => $event,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/event/participe/{id}", name="event_participe")
     * @IsGranted("ROLE_USER")
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

    /**
     * @Route("/event/delete/{id}", name="event_delete")
     * @IsGranted("ROLE_USER")
     */
    public function deleteAction($id): Response
    {

        $user = $this->getUser();

        $event = $this->getDoctrine()->getRepository(Event::class)->findOneBy(array('id' => $id, 'idUser' => $user));

        $entityManager = $this->getDoctrine()->getManager();
        $event->setStatut('2');
        $entityManager->persist($event);
        $entityManager->flush();

        return $this->redirectToRoute('event');

        //   $message = "événement supprimer";

        //  return new Response(json_encode(array('message' => $message, 'result' => 'success')));

    }

    /**
     * @Route("/event/participe/delete/{id}", name="event_participe_delete")
     * @IsGranted("ROLE_USER")
     */
    public function deleteParticipeAction($id): Response
    {

        $user = $this->getUser();

        $participe = $this->getDoctrine()->getRepository(ParticipationEvent::class)->findOneBy(array('idEvent' => $id, 'idUser' => $user));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($participe);
        $entityManager->flush();

        $message = "participation supprimer";

        return new Response(json_encode(array('message' => $message, 'result' => 'success')));
    }

    /**
     * @Route("/like/delete/{id}", name="event_delete_like")
     * @IsGranted("ROLE_USER")
     */
    public function likeDeleteAction($id): Response
    {
        $user = $this->getUser();

        $like = $this->getDoctrine()->getRepository(Like::class)->findOneBy(array('idEvent' => $id, 'idUser' => $user));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($like);
        $entityManager->flush();

        $message = "like supprimer";

        return new Response(json_encode(array('message' => $message, 'result' => 'success')));
    }

}