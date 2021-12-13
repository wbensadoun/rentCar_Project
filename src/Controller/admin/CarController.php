<?php

namespace App\Controller\admin;

use App\Entity\Car;
use App\Form\CarType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/voitures")
 */
class CarController extends AbstractController
{
    /**
     * @Route("/creer",name="create_car_admin")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    { //Injection de dépendance
        $car = new Car(); //Nouvelle objet car

        $form = $this->createForm(CarType::class, $car); //createForm appartient a AbsatractController
        //$form = instance de formulaire dériver du modèle carType

        $form->handleRequest($request); //Ecoute l'action faite sur le form

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($car); //Prépare la requête  avant de l'executer;
            $entityManager->flush();
            $this->addFlash("success", "La voiture à été ajouté"); //Message à envoyer une fois l'objet créer

            return $this->redirectToRoute("create_car"); // actualise la page une fois l'objet créer
        }

        return $this->render("car/index.html.twig", [
            "form" => $form->createView(),
            "action" => "create"
        ]);
    }


    /**
     * @Route("/{id}/modifier", name="edit_car_admin")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Car $car//Mapping de l'objet car
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, Car $car)//Injection de dépendance
    {
        $form = $this->createForm(CarType::class, $car);

        $form->handleRequest($request); //Ecoute l'action faite sur le form

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($car); //Prépare la requête  avant de l'executer;
            $entityManager->flush();
            $this->addFlash("success", "La voiture à été modifier"); //Message à envoyer une fois l'objet modfier

            return $this->redirectToRoute("edit_car",["id" => $car->getId()]); // actualise la page une fois l'objet modifier
        }

        return $this->render("car/index.html.twig", [
            "form" => $form->createView(),
            "action" => "edit"

        ]);
    }

    /**
     * @Route("/list", name="list_cars_admin")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $cars = $entityManager->getRepository(Car::class)->findBy(["state"=>Car::STATE_ENABLE]);
        //On recherche tout les véhicules de l'objet "Car" dont le champ state est = à la const STATE_ENABLE

        return $this->render("car/list.html.twig",[
            "cars" => $cars
            ]);
    }

    /**
     * @Route("/{id}/supprimer", name="delete_car_admin")
     * @param EntityManagerInterface $entityManager
     * @param Car $car
     */
    public function disable(EntityManagerInterface $entityManager, Car $car)
    {
        $car->setState(Car::STATE_DISABLE);
        $entityManager->persist($car); //COMMIT
        $entityManager->flush(); //PUSH
        $this->addFlash('@success', "La voiture à été supprimer");
        return $this->redirectToRoute("list_cars_admin");

    }

}