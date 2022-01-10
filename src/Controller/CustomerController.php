<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Form\ProfileType;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/clients")
 * @IsGranted("ROLE_CUSTOMER")
 */
class CustomerController extends AbstractController
{

    /**
     * @Route("/enregistrement", name="customer_register")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasherInterface
     * @return Response
     */
    public function registerCustomer(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $customer = new Customer();
        $form = $this->createForm(RegisterType::class, $customer);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
          $password = $form->get("user")->get("confirmPassword")->getData();
          $hashedPassword =  $userPasswordHasherInterface->hashPassword($customer->getUser(), $password);
          $customer->getUser()->setPassword($hashedPassword);
          $customer->getUser()->setEnabled(true);
          $customer->getUser()->addRole("ROLE_CUSTOMER");
          $entityManager->persist($customer);
          $entityManager->flush();
          $this->addFlash("success", "Inscription validé !"); //Message à envoyer une fois l'objet crée
            return $this->redirectToRoute("default");// Doit être rediriger sur la page dashboard
        }
        return $this->render("register/form.html.twig",[
            "form"=>$form->createView()
        ]);
    }

    /**
     * @Route("/extranet/profil", name="customer_profiler")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasherInterface
     */
    public function profileCustomer(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasherInterface){
        $customer = $this->getUser()->getCustomer();
        $form = $this->createForm(ProfileType::class, $customer);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $password = $form->get("user")->get("confirmPassword")->getData();

            if($password !==null){
                $hashedPassword =  $userPasswordHasherInterface->hashPassword($customer->getUser(), $password);
                $customer->getUser()->setPassword($hashedPassword);
            }
                $entityManager->persist($customer);
                $entityManager->flush();
                $this->addFlash("success", "Modification validé !"); //Message à envoyer une fois l'objet crée
                return $this->redirectToRoute("customer_profiler");// Doit être rediriger sur la page dashboard
        }
        return $this->render("profile/form.html.twig", [
            "form"=>$form->createView()
        ]);
    }
}