<?php

namespace App\Repository;

use App\Entity\Event;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Event::class);
    }

     /**
      * @return Event[] Returns an array of Event objects
      */
    public function findByDate()
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.dateEvent >= :val')
            ->andWhere('e.statut = :statut')
            ->setParameter('val', new \DateTime('now'))
            ->setParameter('statut', 1)
            ->orderBy('e.id', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;

    }

    public function findEventByUser($user, $event)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.id = :event')
            ->andWhere('e.idUser = :user')
            ->setParameter('event', $event)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
