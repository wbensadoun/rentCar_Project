<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Repository\AdvertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
/**
 * @Route("/annonces")
 */
class PublicAdvertController extends AbstractController
{
    /**
     * @Route("/list", name="public_list")
     * @param EntityManagerInterface $em
     */
    public function public_list(EntityManagerInterface $em)
    {
      $publicAdverts = $em->getRepository(Advert::class)->findAll();
      return $this->render("public_advert/index.html.twig",[
          'public_adverts'=> $publicAdverts
      ]);
    }
}
