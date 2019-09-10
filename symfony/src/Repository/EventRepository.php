<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Tag;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @return Event[]
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

    public function findLatest(int $page = 1, Tag $tag = null): Paginator
    {
        $qb = $this->createQueryBuilder('e')
            ->addSelect('a', 't')
            ->innerJoin('e.idUser', 'a')
            ->leftJoin('e.tags', 't')
            ->where('e.createAt <= :now')
            ->andWhere('e.statut = :statut')
            ->orderBy('e.createAt', 'DESC')
            ->setParameter('now', new \DateTime())
            ->setParameter('statut', 1)
        ;

        if (null !== $tag) {
            $qb->andWhere(':tag MEMBER OF e.tags')
                ->setParameter('tag', $tag);
        }

        return (new Paginator($qb))->paginate($page);
    }

    /**
     * @return Event[]
     */
    public function findBySearchQuery(string $query, int $limit = Event::NUM_ITEMS): array
    {
        $searchTerms = $this->extractSearchTerms($query);

        if (0 === \count($searchTerms)) {
            return [];
        }

        $queryBuilder = $this->createQueryBuilder('e');

        foreach ($searchTerms as $key => $term) {
            $queryBuilder
                ->orWhere('e.title LIKE :t_'.$key)
                ->setParameter('t_'.$key, '%'.$term.'%')
            ;
        }

        return $queryBuilder
            ->orderBy('e.createAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Transforms the search string into an array of search terms.
     */
    private function extractSearchTerms(string $searchQuery): array
    {
        $searchQuery = trim(preg_replace('/[[:space:]]+/', ' ', $searchQuery));
        $terms = array_unique(explode(' ', $searchQuery));

        // ignore the search terms that are too short
        return array_filter($terms, function ($term) {
            return 2 <= mb_strlen($term);
        });
    }

}
