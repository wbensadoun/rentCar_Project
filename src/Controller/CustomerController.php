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
 *
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
     * @Route("/extranet/edit", name="customer_profiler")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasherInterface
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */

    public function profileCustomer(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasherInterface){
        $customer = $this->getUser()->getCustomer();
        $form = $this->createForm(ProfileType::class, $customer);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(!empty($customer->getLicencePicture())){
                /**
                 * @var UploadedFile $licencePicture
                 */
                $licencePicture = $customer->getLicencePicture(); //Récupère le fichier uploadé sous forme d'objet Uploaded file
                $extension = $licencePicture->guessExtension(); //Récupère l'extension grâce à la méthode Uploaded File
                $fileName = $this->giveUniqName().".".$extension; //Donne un nom unique au fichier
                $licencePicture->move($this->getParameter('documents_directory'), $fileName); //Déplace le ficher dans le dossier de stockage
                $customer->setLicencePictureOrigFileName($licencePicture->getClientOriginalName()); //On stocke le nom originale du fichier pour le récupérer
                $customer->setLicencePicture($fileName); //Stocke le nom Unique du fichier
            }

            if(!empty($customer->getPhotoProfile())){
                /**
                 * @var UploadedFile $photoProfile
                 */
                $photoProfile = $customer->getPhotoProfile();
                $extension = $photoProfile->guessExtension();
                $fileName = $this->giveUniqName().".".$extension;
                $photoProfile->move($this->getParameter('documents_directory'), $fileName);
                $customer->setLicencePictureOrigFileName($photoProfile->getClientOriginalName());
                $customer->setPhotoProfile($fileName);
            }

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