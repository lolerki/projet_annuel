<?php

namespace App\Repository;

use App\Entity\ParticipationEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ParticipationEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParticipationEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParticipationEvent[]    findAll()
 * @method ParticipationEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipationEventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ParticipationEvent::class);
    }

    // /**
    //  * @return ParticipationEvent[] Returns an array of ParticipationEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ParticipationEvent
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
