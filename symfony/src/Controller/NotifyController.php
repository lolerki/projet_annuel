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
        $entityManager = $this->getDoctrine()->getManager();

        $notifies = $this->getDoctrine()->getRepository(Notify::class)->findBy(['idUser' => $user]);

        $notifyNotRead = $this->getDoctrine()->getRepository(Notify::class)->findBy(['idUser' => $user, 'read' => 'false']);

        foreach ($notifyNotRead as $notify){

            $notify->setRead(1);
            $entityManager->persist($notify);

        }

        $entityManager->flush();

        return $this->render('notify/show.html.twig', [
            'notifys' => $notifies
        ]);

    }

}

