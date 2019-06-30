<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Demands;
use App\Repository\DemandsRepository;
//use App\Entity\Events;
use App\Form\EventsType;
//use App\Repository\EventsRepository;



/**
 * @Route("/")
 *
 * Class DefaultController
 * @package App\Controller
 */
class HomeController extends AbstractController
{

    /**
     * @Route("/", name="app_index")
     */
    public function indexAction(): Response
    {


        return $this->render('home/home.html.twig');
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
     * @Route("/booking", name="app_booking")
     */
    public function bookingAction(): Response
    {
        return $this->render('home/booking.html.twig');
    }

    /**
     * @Route("/artists", name="app_bookingartists")
     */
    public function bookingArtistsAction(): Response
    {
        return $this->render('home/artists.html.twig');
    }


    /**
     * @Route("/lists-events", name="app_bookingevent")
     */
 /*   public function bookingEventAction(EventsRepository $eventsRepository): Response
    {
        return $this->render('home/events.html.twig',[
            'events' => $eventsRepository->findAll(),
        ]);
    }*/

    /**
     * @Route("/about", name="app_about")
     */
    public function aboutAction(): Response
    {
        return $this->render('home/about.html.twig');
    }

    /**
     * @Route("/profil", name="app_profil")
     */
    public function profilAction(DemandsRepository $demandsRepository): Response
    {
        return $this->render('home/profil.html.twig',
            [
                'demands' => $demandsRepository->findAll(),
            ]);
    }
}

