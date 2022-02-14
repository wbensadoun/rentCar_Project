<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Repository\AdvertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
      $publicAdverts = $em->getRepository(Advert::class)->findByLastAdverts();
      return $this->render("public_advert/index.html.twig",[
          'public_adverts'=> $publicAdverts
      ]);
    }

    /**
     * @Route("/detail/{id}", name="public_detail")
     */
    public function detail_advert(Advert $advert)
    {
        return $this->render("public_advert/detail_advert.html.twig",[
            'advert'=>$advert
        ]);
    }

    /**
     * @Route("/search", name="public_search")
     */
    public function search(EntityManagerInterface $em, Request $request)
    {
        $searchKey = $request->query->get("search");
        $getResearch = $em->getRepository(Advert::class)->findByKeyWord($searchKey);

        return $this->render("public_advert/index.html.twig",[
            'public_adverts'=>$getResearch
            ]);
    }
}
