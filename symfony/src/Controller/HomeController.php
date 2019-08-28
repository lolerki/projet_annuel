<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;


/**
 * @Route("/")
 *
 * Class DefaultController
 * @package App\Controller
 */
class HomeController extends AbstractController
{

    /**
     * @Route("/home", name="test")
     */
    public function indexAction(): Response
    {

  //      $user = $this->getUser();

        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();



        return $this->render('home/home.html.twig', [
            'events' => $events
        ]);

    }

    /**
     * @Route("/mentions-legales", name="app_mentions-legales")
     */
    public function mentionLegaleAction(): Response
    {
        return $this->render('home/mentionslegales.html.twig');
    }

    /**
     * @Route("/cgv", name="app_cgv")
     */
    public function cgvAction(): Response
    {
        return $this->render('home/cgv.html.twig');
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

