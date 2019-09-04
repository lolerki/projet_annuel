<?php

namespace App\Controller;

use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use App\Entity\Like;



/**
 * @Route("/")
 *
 * Class DefaultController
 * @package App\Controller
 */
class HomeController extends AbstractController
{

    /**
     * @Route("/home", name="app_home")
     */
    public function indexAction(): Response
    {

        $events = $this->getDoctrine()->getRepository(Event::class)->findBy(['statut' => 1], ['id' => 'desc']);

        $nextEvent =  $this->getDoctrine()->getRepository(Event::class)->findByDate();

        return $this->render('home/home.html.twig', [
            'events' => $events,
            'nextEvents' => $nextEvent
        ]);

    }

    /**
     * @Route("/like/{id}", name="event_like")
     */
    public function likeAction($id): Response
    {

        $user = $this->getUser();

        $like = new Like();

        $event = $this->getDoctrine()->getRepository(Event::class)->findOneBy(['id' => $id]);

        $rechercheLike = $this->getDoctrine()->getRepository(Like::class)->findOneBy(['idEvent' => $event, 'idUser' => $user]);

        if ($rechercheLike != null) {

            $message = "";

            return new Response(json_encode(array('message' => $message, 'result' => 'success')));

        } else {

            $entityManager = $this->getDoctrine()->getManager();
            $like->setIdUser($user);
            $like->setIdEvent($event);
            $entityManager->persist($like);
            $entityManager->flush();

            $message = "";

            return new Response(json_encode(array('message' => $message, 'result' => 'success')));

        }
    }

    /**
     * @Route("/mentions-legales", name="app_mentions_legales")
     */
    public function mentionLegaleAction(): Response
    {
        return $this->render('home/mentionslegales.html.twig');
    }

    /**
     * @Route("/help", name="help")
     */
    public function aideAction(): Response
    {
        return $this->render('home/help.html.twig');
    }

    /**
     * @Route("/contact", name="app_contact")
     */
    public function contactAction(): Response
    {
        return $this->render('home/contact.html.twig');
    }

    /**
     * @Route("/about", name="app_about")
     */
    public function aboutAction(): Response
    {
        return $this->render('home/about.html.twig');
    }
}

