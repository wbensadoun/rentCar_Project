<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/default", name="default")
     */
    public function index(): Response
    {
        $utilisateur = [
            "ludovic", "wissal"," azerty"
        ];
        return $this->render('default/index.html.twig', [
            'mon_parametre' => 'COUCOU',
            'users' => $utilisateur
        ]);
    }
}
