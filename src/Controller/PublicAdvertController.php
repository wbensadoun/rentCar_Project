<?php

namespace App\Controller;
use Symfony\Component\Mime\Email;
use App\Entity\Advert;
use App\Repository\AdvertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
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

    /**
     * @Route("/test-mail")
     * @param MailerInterface $mailer
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function testMail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);
    }
}
