<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Notify;


class NotifyController extends AbstractController
{

    /**
     * @Route("/notify", name="notify")
     */
    public function indexAction(): Response
    {



        return $this->render('notify/show.html.twig', [

        ]);

    }

}

