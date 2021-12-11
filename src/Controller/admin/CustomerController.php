<?php

namespace App\Controller\admin;

use App\Entity\Customer;
use App\Form\CustomerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin/clients")
 */
class CustomerController extends AbstractController
{
    /**
     * @Route("/creer",name="create_customer")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    { //Injection de dépendance
        $customer = new Customer(); //Nouvelle objet Customer

        $form = $this->createForm(CustomerType::class, $customer); //createForm appartient a AbsatractController
        //$form = instance de formulaire dériver du modèle du customer En gros il créer un formulaire sur la base des colonnes dans la table customer

        $form->handleRequest($request); //Ecoute l'action faite sur le form

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($customer); //Prépare la requête  avant de l'executer;
            $entityManager->flush();
            $this->addFlash("success", "Inscription validé !"); //Message à envoyer une fois l'objet créer

            return $this->redirectToRoute("create_customer"); // actualise la page une fois l'objet créer
        }

        return $this->render("customer/index.html.twig", [
            "form" => $form->createView(),
            "action" => "create"
        ]);
    }



    /**
     * @Route("/{id}/modifier", name="edit_customer")
     * @param Customer $customer //Mapping de l'objet customer
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, Customer $customer)//Injection de dépendance
    {
        $form = $this->createForm(CustomerType::class, $customer);

        $form->handleRequest($request); //Ecoute l'action faite sur le form

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($customer); //Prépare la requête  avant de l'executer;
            $entityManager->flush();
            $this->addFlash("success", "Le profil à été modifier"); //Message à envoyer une fois l'objet modfier

            return $this->redirectToRoute("edit_customer", ["id" => $customer->getId()]); // actualise la page une fois l'objet modifier
        }

        return $this->render("customer/index.html.twig", [
            "form" => $form->createView(),
            "action" => "edit"

        ]);
    }
}