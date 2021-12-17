<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/clients")
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
     * @return string
     */
    private function giveUniqName(): string
    {
        return md5(uniqid()); //ça permet de créer un nom unique
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

            $entityManager->persist($customer); //Prépare la requête  avant de l'executer;
            $entityManager->flush();
            $this->addFlash("success", "Le profil à été modifier"); //Message à envoyer une fois l'objet modfier

            return $this->redirectToRoute("edit_customer", ["id" => $customer->getId()]); // actualise la page une fois l'objet modifier
        }

        return $this->render("customer/index.html.twig", [
            "form" => $form->createView(),
            "customer" => $customer,
            "action" => "edit"

        ]);
    }

    /**
     * @Route("/list", name="list_customer")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $customers = $entityManager->getRepository(Customer::class)->findAll();
        //On recherche tout les customer de l'objet "Customer" dont le champ state est = à la const STATE_ENABLE

        return $this->render("customer/list.html.twig",[
            "customers" => $customers
        ]);
    }

    /**
     * @Route("/{id}/supprimer", name="delete_customer")
     * @param EntityManagerInterface $entityManager
     * @param Customer $customer
     */
    public function disable(EntityManagerInterface $entityManager, Customer $customer)
    {

        $entityManager->persist($customer); //COMMIT
        $entityManager->flush(); //PUSH
        $this->addFlash('@success', "Le  client à été supprimer");
        return $this->redirectToRoute("list_customer_admin");

    }
    /**
     * @Route("/licence_picture/{id}", name="get_document_customer")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param Customer $customer
     */
    public function getLicencePicture(EntityManagerInterface $entityManager, Request $request,Customer $customer)
    {
        $file= $this->getParameter("documents_directory").$customer->getLicencePicture();
        return $this->file($file, $customer->getLicencePictureOrigFileName());
    }

    /**
     * @Route("/enregistrement", name="customer_register")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     */
    public function registerCustomer(EntityManagerInterface $entityManager, Request $request)
    {
        $customer = new Customer();
        $form = $this->createForm(RegisterType::class, $customer);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

        }
        return $this->render("register/form.html.twig",[
            "form"=>$form->createView()
        ]);
    }
}