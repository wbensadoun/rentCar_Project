<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function create(){
        $car = new Car(); //Nouvelle objet car

        $form = $this->createForm(CarType::class, $car); //createForm appartient a AbsatractController
        //$form = instance de formulaire dériver du modèle carType

        return $this->render("car/index.html.twig", [
            "form" => $form->createView()
        ]);
    }

}
