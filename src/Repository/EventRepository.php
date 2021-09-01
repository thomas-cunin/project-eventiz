<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
     * @return Event[] Returns an array of Event objects
     */
    public function findAllByCreatedAtAsc() // $order = 'ASC' or 'DESC'
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.createdAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

        /**
     * @return Event[] Returns an array of Event objects
     */
    public function findAllByCreatedAtDesc() // $order = 'ASC' or 'DESC'
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

       /**
     * @return Event[] Returns an array of Event objects
     */
    public function findAllByContent($word) // 
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.content LIKE :val')
            ->setParameter('val', '%'.$word.'%')
            ->orderBy('e.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    //   /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    // public function findAllByUser($user) // 
    // {
    //     return $this->createQueryBuilder('e')
    //         ->innerjoin('e.user', 'u')
    //         ->andWhere('u.username = :val')
    //         ->setParameter('val', $user)
    //         ->orderBy('e.createdAt', 'DESC')
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
