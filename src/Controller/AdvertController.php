<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Form\AdvertType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/extranet/annonces")
 */
class AdvertController extends AbstractController
{
    /**
     * @Route("/creer", name="create_advert")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $advert = new Advert();
        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($advert);
            $entityManager->flush();
            $this->addFlash("success", "L'annonce à été ajouté"); //Message à envoyer une fois l'objet créer
            return $this->redirectToRoute("create_advert"); // actualise la page une fois l'objet créer

        }

        return $this->render("advert/edit.html.twig", [
            "form" => $form->createView(),
            "action" => "create"
        ]);
    }
    /**
     * @Route("/{id}/modifier", name="edit_advert")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Advert $advert  //Mapping de l'objet advert
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, Advert $advert) //Injection de dépendance
    {
        $form = $this->createForm(AdvertType::class, $advert);

        $form->handleRequest($request); //Ecoute l'action faite sur le form

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($advert); //Prépare la requête  avant de l'executer;
            $entityManager->flush();
            $this->addFlash("success", "L'annonce à été modifier"); //Message à envoyer une fois l'objet modfier

            return $this->redirectToRoute("edit_advert", ["id" => $advert->getId()]); // actualise la page une fois l'objet modifier
        }

        return $this->render("advert/edit.html.twig", [
            "form" => $form->createView(),
            "action" => "edit"

        ]);
    }
    /**
     * @Route("/list", name="list_adverts")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $adverts = $entityManager->getRepository(Advert::class)->findBy(["state" => Advert::STATE_ENABLE]);
        //On recherche tout les véhicules de l'objet "advert" dont le champ state est = à la const STATE_ENABLE

        return $this->render("advert/index.html.twig", [
            "adverts" => $adverts
        ]);
    }

    /**
     * @Route("/{id}/supprimer", name="delete_advert")
     * @param EntityManagerInterface $entityManager
     * @param Advert $advert
     */
    public function disable(EntityManagerInterface $entityManager, Advert $advert)
    {
        $advert->setState(Advert::STATE_DISABLE);
        $entityManager->persist($advert); //COMMIT
        $entityManager->flush(); //PUSH
        $this->addFlash('@success', "L'annonce à été supprimer");
        return $this->redirectToRoute("list_adverts");
    }
}
