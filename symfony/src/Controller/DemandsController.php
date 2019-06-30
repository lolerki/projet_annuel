<?php

namespace App\Controller;

use App\Entity\Demands;
use App\Form\DemandsType;
use App\Repository\DemandsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/demands")
 */
class DemandsController extends AbstractController
{
    /**
     * @Route("/", name="demands_index", methods={"GET"})
     */
    public function index(DemandsRepository $demandsRepository): Response
    {
        return $this->render('demands/index.html.twig', [
            'demands' => $demandsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="demands_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $demand = new Demands();
        $form = $this->createForm(DemandsType::class, $demand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($demand);
            $entityManager->flush();

            return $this->redirectToRoute('demands_index');
        }

        return $this->render('demands/new.html.twig', [
            'demand' => $demand,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="demands_show", methods={"GET"})
     */
    public function show(Demands $demand): Response
    {
        return $this->render('demands/show.html.twig', [
            'demand' => $demand,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="demands_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Demands $demand): Response
    {
        $form = $this->createForm(DemandsType::class, $demand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('demands_index', [
                'id' => $demand->getId(),
            ]);
        }

        return $this->render('demands/edit.html.twig', [
            'demand' => $demand,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="demands_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Demands $demand): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demand->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($demand);
            $entityManager->flush();
        }

        return $this->redirectToRoute('demands_index');
    }
}
