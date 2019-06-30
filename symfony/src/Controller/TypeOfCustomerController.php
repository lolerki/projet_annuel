<?php

namespace App\Controller;

use App\Entity\TypeOfCustomer;
use App\Form\TypeOfCustomerType;
use App\Repository\TypeOfCustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/type/of/customer")
 */
class TypeOfCustomerController extends AbstractController
{
    /**
     * @Route("/", name="type_of_customer_index", methods={"GET"})
     */
    public function index(TypeOfCustomerRepository $typeOfCustomerRepository): Response
    {
        return $this->render('type_of_customer/index.html.twig', [
            'type_of_customers' => $typeOfCustomerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="type_of_customer_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $typeOfCustomer = new TypeOfCustomer();
        $form = $this->createForm(TypeOfCustomerType::class, $typeOfCustomer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typeOfCustomer);
            $entityManager->flush();

            return $this->redirectToRoute('type_of_customer_index');
        }

        return $this->render('type_of_customer/new.html.twig', [
            'type_of_customer' => $typeOfCustomer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_of_customer_show", methods={"GET"})
     */
    public function show(TypeOfCustomer $typeOfCustomer): Response
    {
        return $this->render('type_of_customer/show.html.twig', [
            'type_of_customer' => $typeOfCustomer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="type_of_customer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TypeOfCustomer $typeOfCustomer): Response
    {
        $form = $this->createForm(TypeOfCustomerType::class, $typeOfCustomer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('type_of_customer_index', [
                'id' => $typeOfCustomer->getId(),
            ]);
        }

        return $this->render('type_of_customer/edit.html.twig', [
            'type_of_customer' => $typeOfCustomer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_of_customer_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TypeOfCustomer $typeOfCustomer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeOfCustomer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($typeOfCustomer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('type_of_customer_index');
    }
}
