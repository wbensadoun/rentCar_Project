<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mime\Email;
use App\Entity\Advert;
use App\Repository\AdvertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
      $publicAdverts = $em->getRepository(Advert::class)->findByLastAdverts(10);
      return $this->render("public_advert/index.html.twig",[
          'public_adverts'=> $publicAdverts
      ]);
    }

    /**
     * @Route("/load-more/{offset}", name="more_advert")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param int $offset
     */
    public function loadMoreAdvert(EntityManagerInterface $entityManager, Request $request, int $offset)
    {
        $publicAdverts = $entityManager->getRepository(Advert::class)->findByLastAdverts(10, $offset);
        $render = $this->render("public_advert/advert.html.twig",[
            'public_adverts'=>$publicAdverts
        ])->getContent();
        return new JsonResponse($render);
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
