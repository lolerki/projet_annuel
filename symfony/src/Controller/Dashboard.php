<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class Dashboard extends AbstractController
{

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function indexAction(): Response
    {


        return $this->render('dashboard/dashboard.html.twig');
    }

}