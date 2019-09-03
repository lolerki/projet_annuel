<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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
        $userProfile = $user->getProfile();

        if($userProfile == null){
            return $this->redirectToRoute('profile_new');
        }

        $profileExiste = false;

        if($userProfile != null){
            $profileExiste = true;
        }

        return $this->render('dashboard/dashboard.html.twig', [
            'linkProfile' => $profileExiste,
            'idProfile' => $userProfile
        ]);
    }

}