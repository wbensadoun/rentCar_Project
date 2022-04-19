<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Rental;
use App\Form\CarType;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/extranet/voitures")
 * @IsGranted("ROLE_CUSTOMER")
 */
class CarController extends AbstractController
{
    /**
     * @Route("/creer",name="create_car")
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
            if( !empty($car->getPicture1()) ){
                /**
                 * @var UploadedFile $picture1
                 */
                $picture1 = $car->getPicture1();
                $extension = $picture1->guessExtension();
                $fileName = $this->giveUniqName(). "." .$extension;
                $picture1->move($this->getParameter("images_directory"),$fileName);
                $car->setPicture1($fileName);
                $car->setPicture1OrigFileName($picture1->getClientOriginalName());
            }
            if( !empty($car->getPicture2()) ){
                /**
                 * @var UploadedFile $picture2
                 */
                $picture2 = $car->getPicture2();
                $extension = $picture2->guessExtension();
                $fileName = $this->giveUniqName(). "." .$extension;
                $picture2->move($this->getParameter("images_directory"),$fileName);
                $car->setPicture2($fileName);
                $car->setPicture2OrigFileName($picture2->getClientOriginalName());
            }
            if( !empty($car->getPicture3()) ){
                /**
                 * @var UploadedFile $picture3
                 */
                $picture3 = $car->getPicture3();
                $extension = $picture3->guessExtension();
                $fileName = $this->giveUniqName(). "." .$extension;
                $picture3->move($this->getParameter("images_directory"),$fileName);
                $car->setPicture3($fileName);
                $car->setPicture3OrigFileName($picture3->getClientOriginalName());
            }
            $car->setCustomer($this->getUser()->getCustomer());
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
     * @Route("/{id}/modifier", name="edit_car")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Car $car//Mapping de l'objet car
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, Car $car)//Injection de dépendance
    {
        $form = $this->createForm(CarType::class, $car);
        $initialPicture1 = $car->getPicture1();
        $initialPicture2 = $car->getPicture2();
        $initialPicture3 = $car->getPicture3();

        $form->handleRequest($request); //Ecoute l'action faite sur le form

        if ($form->isSubmitted() && $form->isValid()) {
            if( !empty($car->getPicture1()) ){
                /**
                 * @var UploadedFile $picture1
                 */
                $picture1 = $car->getPicture1();
                $extension = $picture1->guessExtension();
                $fileName = $this->giveUniqName(). "." .$extension;
                $picture1->move($this->getParameter("images_directory"),$fileName);
                $car->setPicture1($fileName);
                $car->setPicture1OrigFileName($picture1->getClientOriginalName());
                $fileSysteme = new Filesystem();
                $fileSysteme->remove($this->getParameter("images_directory").$initialPicture1);
            }else{
                $car->setPicture1($initialPicture1);
            }
            if( !empty($car->getPicture2()) ){
                /**
                 * @var UploadedFile $picture2
                 */
                $picture2 = $car->getPicture2();
                $extension = $picture2->guessExtension();
                $fileName = $this->giveUniqName(). "." .$extension;
                $picture2->move($this->getParameter("images_directory"),$fileName);
                $car->setPicture2($fileName);
                $car->setPicture2OrigFileName($picture2->getClientOriginalName());
                $fileSysteme = new Filesystem();
                $fileSysteme->remove($this->getParameter("images_directory").$initialPicture2);
            }else{
                $car->setPicture2($initialPicture2);
            }
            if( !empty($car->getPicture3()) ){
                /**
                 * @var UploadedFile $picture3
                 */
                $picture3 = $car->getPicture3();
                $extension = $picture3->guessExtension();
                $fileName = $this->giveUniqName(). "." .$extension;
                $picture3->move($this->getParameter("images_directory"),$fileName);
                $car->setPicture3($fileName);
                $car->setPicture3OrigFileName($picture3->getClientOriginalName());
                $fileSysteme = new Filesystem();
                $fileSysteme->remove($this->getParameter("images_directory").$initialPicture3);
            }else{
                $car->setPicture3($initialPicture3);
            }
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
     * @Route("/list", name="list_cars")
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
     * @Route("/{id}/supprimer", name="delete_car")
     * @param EntityManagerInterface $entityManager
     * @param Car $car
     */
    public function disable(EntityManagerInterface $entityManager, Car $car)
    {
        $car->setState(Car::STATE_DISABLE);
        $entityManager->persist($car); //COMMIT
        $entityManager->flush(); //PUSH
        $this->addFlash('@success', "La voiture à été supprimer");
        return $this->redirectToRoute("list_cars");

    }

    /**
     * @return string
     */
    private function giveUniqName(): string
    {
        return md5(uniqid()); //ça permet de créer un nom unique
    }

    /**
     * @Route("/{id}/location", name="rental_car")
     * @param EntityManagerInterface $entityManager
     * @param Car $car
     * @param MailerInterface $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|void
     */
    public function rentalCar(EntityManagerInterface $entityManager, Car $car, MailerInterface $mailer)
    {
        $rental = new Rental();
        $rental->setCar($car);
        $rental->setStartDate(new \DateTime("now"));
        $rental->setCustomer($this->getUser()->getCustomer());
        try{
            $entityManager->persist($rental);
        }catch (Exception $e){
            $this->addFlash("danger", "Une erreur est arrivé, veuillez recommencer ".$e->getMessage());
            return $this->redirectToRoute("default");
        }
        $car->setState(Car::STATE_RENTALE);
        $entityManager->persist($car);
        $entityManager->flush();

        $email = (new Email())
        ->from($this->getParameter('mail_from'))
            ->to($this->getUser()->getEmail())
            ->subject("Confirmation de la location")
            ->html($this->render("mail/rental.customer.html.twig",[
                'userName'=>$this->getUser()->getUserIdentifier(),
                'loueurName'=>$car->getCustomer()->getUser()->getUserIdentifier()
            ])->getContent())
            ;
        $mailer->send($email);

        $email = (new Email())
            ->from($this->getParameter('mail_from'))
            ->to($car->getCustomer()->getUser()->getEmail())
            ->subject("Confirmation de la location")
            ->html($this->render("mail/rental.car.customer.html.twig",[
                'userName'=>$car->getCustomer()->getUser()->getUserIdentifier(),
                'carName' => $car->getName(),
                'customerName'=>$this->getUser()->getUserIdentifier()
            ])->getContent())
        ;
        $mailer->send($email);

        $this->addFlash("success", "Validé, l'email à été bien envoyé.");
        return $this->redirectToRoute('default');
    }
}
