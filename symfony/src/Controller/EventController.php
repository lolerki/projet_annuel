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
use App\Entity\Notify;
use App\Entity\Comment;
use Algolia\SearchBundle\IndexManagerInterface;


class EventController extends AbstractController
{

    protected $indexManager;

    public function __construct(IndexManagerInterface $indexingManager)
    {
        $this->indexManager = $indexingManager;
    }

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

        //event créer par l'utilisateur
        $myEvents =  $this->getDoctrine()->getRepository(Event::class)->findByEventDate($user);

        //event participé
        $events = $this->getDoctrine()->getRepository(ParticipationEvent::class)->findBy(array('idUser' => $user));

        //event save
        $aves = $this->getDoctrine()->getRepository(Like::class)->findBy(array('idUser' => $user));

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $event->setIdUser($user);
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_show', ['id' => $event->getId()]);

        }
        return $this->render('event/event.html.twig', [
            'form' => $form->createView(),
            'events' => $myEvents,
            'likes' => $aves,
            'user' => $user,
            'myevents' => $events
        ]);
    }

    /**
     * @Route("/event/new", name="create_event")
     * @IsGranted("ROLE_USER")
     */
    public function createAction(Request $request): Response
    {

        $user = $this->getUser();
        $event = new Event();

        if($user->getProfile() == null){
            return $this->redirectToRoute('profile_new');
        }

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get("imageFile")->getData();

            if($image == null){
                $event->setImage('iStock-667709450.jpg');
            }

            $entityManager = $this->getDoctrine()->getManager();
            $event->setIdUser($user);
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_show', ['id' => $event->getId()]);

        }
        return $this->render('event/create.html.twig', [
            'form' => $form->createView(),
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

            return $this->redirectToRoute('event_show', ['id' => $event->getId()]);
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

        if($event->getNbPlace() == 0){
            $message = "Aucune place disponible";

            return new Response(json_encode(array('message' => $message, 'result' => 'fail')));
        }

        $placeRetante = $event->getNbPlace() - 1;

        $entityManager = $this->getDoctrine()->getManager();
        $participer->setIdUser($user);
        $participer->setIdEvent($event);
        $event->setNbPlace($placeRetante);
        $entityManager->persist($participer);
        $entityManager->persist($event);
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
        $usersParticipes =  $this->getDoctrine()->getRepository(ParticipationEvent::class)->findBy(['idEvent' => $event]);

        $entityManager = $this->getDoctrine()->getManager();

        $message = "L'événement ".$event->getTitle()." à été annulée";
        $title = "Annulation d'un événement";

        if($usersParticipes != null){

            foreach ($usersParticipes as $userParticipe){

                $notify = new Notify();

                //user object
                $user = $userParticipe->getIdUser();

                $notify->setIdUser($user);
                $notify->setTitle($title);
                $notify->setRead(false);
                $notify->setContent($message);

                $entityManager->persist($notify);

            }

        }

        $event->setStatut('2');
        $entityManager->persist($event);
        $entityManager->flush();

        $message = "<i class=\"far fa-trash-alt\"></i> l'événement a bien été supprimé";

        return new Response(json_encode(array('message' => $message, 'result' => 'success')));

    }

    /**
     * @Route("/event/participe/delete/{id}", name="event_participe_delete")
     * @IsGranted("ROLE_USER")
     */
    public function deleteParticipeAction($id): Response
    {

        $user = $this->getUser();

        $participe = $this->getDoctrine()->getRepository(ParticipationEvent::class)->findOneBy(array('idEvent' => $id, 'idUser' => $user));
        $event = $this->getDoctrine()->getRepository(Event::class)->findOneBy(array('id' => $id));

        $newNbPlace = $event->getNbPlace() +1;

        $entityManager = $this->getDoctrine()->getManager();
        $event->setNbPlace($newNbPlace);
        $entityManager->remove($participe);
        $entityManager->flush();

        $message = '<i class="fas fa-info-circle"></i> Votre participation a été annulée';

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