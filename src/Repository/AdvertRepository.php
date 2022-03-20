<?php

namespace App\Repository;

use App\Entity\Advert;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Advert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advert[]    findAll()
 * @method Advert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advert::class);
    }
    public function findMyLastAdverts(int $nbAdvert = 3, int $offset = 1, User $user)
    {
        return $this->createQueryBuilder("a")
            ->join('a.Car', 'car')
            ->join("car.customer", "customer")
            ->join("customer.user","user")
            ->where("user=:user")
            ->setParameter(':user', $user)
            ->orderBy('a.createDate', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($nbAdvert)
            ->getQuery()
            ->getResult()
            ;
    }
    public function findByLastAdverts(int $nbAdvert = 3, int $offset = 1)
    {
        return $this->createQueryBuilder("a")
            ->orderBy('a.createDate', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($nbAdvert)
            ->getQuery()
            ->getResult()
            ;

    }

    public function findByKeyWord(string $keyWord)
    {
        return $this->createQueryBuilder("a")
            ->innerJoin("a.Car","car")
            ->where('a.titre LIKE :keyWord')
            ->orWhere("car.model LIKE :keyWord")
            ->setParameter(":keyWord", "%".$keyWord."%")
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Advert[] Returns an array of Advert objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Advert
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
