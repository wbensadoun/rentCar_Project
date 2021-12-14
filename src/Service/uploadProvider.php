<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Car;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Class UploadFileManager
 * @package App\UserBundle\Helpers
 */
class UploadFileManager
{
    private $fileName;
    private $licencePicture;
    private $customer;
    private $extension;

    /**
     * UploadFileManager constructor
     * @param $fileName
     * @param $licencePicture
     * @param $customer
     * @param $extension
     */
    public function __construct($fileName, $licencePicture, $customer, $extension)
    {
        $this->fileName = $fileName;
        $this->licencePicture = $licencePicture;
        $this->customer = $customer;
        $this->extension = $extension;
    }

    public function uploadFile()
    {
        /**
         *@param $customer
         * @param $entityManager
         */
        if(!empty($customer->getLicencePicture($entityManager, $customer))){
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

    }
}