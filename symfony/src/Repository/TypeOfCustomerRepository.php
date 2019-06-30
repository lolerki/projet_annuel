<?php

namespace App\Repository;

use App\Entity\TypeOfCustomer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeOfCustomer|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeOfCustomer|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeOfCustomer[]    findAll()
 * @method TypeOfCustomer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeOfCustomerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeOfCustomer::class);
    }

    // /**
    //  * @return TypeOfCustomer[] Returns an array of TypeOfCustomer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeOfCustomer
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
