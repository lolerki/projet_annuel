<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Notify;

class NavbarController extends SecurityController
{

    /**
     * @Template()
     */
    public function indexAction(): Response
    {
        $user = $this->getUser();
        $notifies = $this->getDoctrine()->getRepository(Notify::class)->findBy(array('idUser' => $user));
        $notif = false;

        foreach ($notifies as $notify){

            if($notify->getRead()){
                $notif = true;
                break;
            }

        }

        return $this->render('navbar/navbar.html.twig',[
            'notif' => $notif
        ]);
    }
}

