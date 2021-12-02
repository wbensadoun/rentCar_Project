<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/voitures")
 */
class CarController extends AbstractController
{
    /**
     * @Route("/creer",name="create_car")
     */
    public function create(Request $request, EntityManagerInterface $entityManager){ //Injection de dépendance
        $car = new Car(); //Nouvelle objet car

        $form = $this->createForm(CarType::class, $car); //createForm appartient a AbsatractController
        //$form = instance de formulaire dériver du modèle carType

        $form->handleRequest($request); //Ecoute l'action faite sur le form

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($car); //Prépare la requête  avant de l'executer;
            $entityManager->flush();
            $this->addFlash("success", "La voiture à été ajouté"); //Message à envoyer une fois l'objet créer

            return $this->redirectToRoute("create_car"); // actualise la page une fois l'objet créer
        }

        return $this->render("car/index.html.twig", [
            "form" => $form->createView()
        ]);
    }

}
