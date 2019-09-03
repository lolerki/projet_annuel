<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Profile;

 /**
  * Require ROLE_USER for *every* controller method in this class.
  *
  * @IsGranted("ROLE_USER")
  */
class Dashboard extends AbstractController
{

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function indexAction(): Response
    {

        $user = $this->getUser();

        $profileExiste = false;

        $profile = $this->getDoctrine()->getRepository(Profile::class)->findOneBy(array('id_user' => $user));

        if($profile != null){
            $profileExiste = true;
        }

        return $this->render('dashboard/dashboard.html.twig', [
            'linkProfile' => $profileExiste,
            'idProfile' => $profile
        ]);
    }

}