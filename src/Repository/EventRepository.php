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
            ->andWhere('e.content LIKE :val or e.title LIKE :val')
            ->setParameter('val', '%'.$word.'%')
            ->orderBy('e.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

      /**
     * @return Event[] Returns an array of Event objects
     */
    public function findAllByCity($city) // 
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.city LIKE :val')
            ->setParameter('val', '%'.$city.'%')
            ->orderBy('e.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    

    //    /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    // public function findAllByCategorys(...$categorys) // 
    // {
    //     $where = '';
    //     foreach ($categorys as $cat){

    //     }
    //     $qb = $this->createQueryBuilder('e');
    //     foreach ($categorys as $category)
    //     {
    //         $qb->andWhere('e.content LIKE :cat')
    //         ->setParameter('cat', );
    //     }
    //         $qb->orderBy('e.createdAt', 'DESC')
            
            
    //     ;
    //     return $qb->getQuery()->getResult();
    // }

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





    
       /**
     * @return Event[] Returns an array of Event objects
     */
    public function findAllByContentAndCategoryAndCity($query=null, $category=null, $city=null) // 
    {
        
        dump($city);
       $queryBulder = $this->createQueryBuilder('e');
       $parameters = [];

       if($query != null){
        $queryBulder 
        ->andWhere('e.content LIKE :val or e.title LIKE :val');
        $parameters['val'] = '%'.$query.'%';
       }

       if($category != null){
        $queryBulder 
        ->innerJoin('e.category','c')
        ->andWhere('c.id = :category ');
        $parameters['category'] = $category;
       }

       
    //    if($date != null){
    //        $date = (new \DateTime($date))->format('Y-m-d H:i:00');
    //        dump($date);
    //     $queryBulder 
    //     ->andWhere('e.createdAt >= :createdAt');
    //     $parameters['createdAt'] = $startAt;
    //    }

       if($city != null){
        $queryBulder 
        ->andWhere('e.city LIKE :city ');
        $parameters['city'] = '%'.$city.'%';
       }

       $queryBulder 
            ->setParameters($parameters)
            ->orderBy('e.createdAt', 'DESC')
            
            // ->getResult()
        ;
        return $queryBulder->getQuery();
    }
}
