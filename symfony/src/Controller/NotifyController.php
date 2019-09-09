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
        $user = $this->getUser();

        $notify = $this->getDoctrine()->getRepository(Notify::class)->findBy(['idUser' => $user]);

        return $this->render('notify/show.html.twig', [
            'notifys' => $notify
        ]);

    }

}

