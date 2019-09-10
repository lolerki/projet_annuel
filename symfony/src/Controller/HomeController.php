<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use App\Form\ContactType;
use App\Entity\Contact;
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
     * @Route("/", defaults={"page": "1"}, methods={"GET"}, name="app_home")
     * @Route("/page/{page<[1-9]\d*>}", defaults={"_format"="html"}, methods={"GET"}, name="index_paginated")
     * @Cache(smaxage="10")
     *
     */
    public function indexAction(Request $request, int $page, EventRepository $event, TagRepository $tags): Response
    {

        $tag = null;

        if ($request->query->has('tag')) {
            $tag = $tags->findOneBy(['name' => $request->query->get('tag')]);
        }
        $latestEvents = $event->findLatest($page, $tag);

        $events = $this->getDoctrine()->getRepository(Event::class)->findBy(['statut' => 1], ['id' => 'desc']);

        $nextEvent =  $this->getDoctrine()->getRepository(Event::class)->findByDate();

        return $this->render('home/home.html.twig', [
            'paginator' => $latestEvents,
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
    public function contactAction(Request $request): Response
    {

        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();
        }

        return $this->render('home/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/about", name="app_about")
     */
    public function aboutAction(): Response
    {
        return $this->render('home/about.html.twig');
    }

    /**
     * @Route("/search", methods={"GET"}, name="event_search")
     */
    public function search(Request $request, EventRepository $events): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->render('home/search.html.twig');
        }

        $query = $request->query->get('q', '');
        $limit = $request->query->get('l', 10);
        $foundEvents = $events->findBySearchQuery($query, $limit);

        $results = [];
        foreach ($foundEvents as $event) {
            $results[] = [
                'title' => htmlspecialchars($event->getTitle(), ENT_COMPAT | ENT_HTML5),
                'date' => $event->getCreateAt()->format('M d, Y'),
        //        'author' => htmlspecialchars($event->getAuthor()->getFullName(), ENT_COMPAT | ENT_HTML5),
                'content' => htmlspecialchars($event->getDescription(), ENT_COMPAT | ENT_HTML5),
                'url' => $this->generateUrl('event_show', ['id' => $event->getId()]),
            ];
        }

        return $this->json($results);
    }

}

