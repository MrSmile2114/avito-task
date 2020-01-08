<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    /**
     * ItemRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function getMaxId(): int
    {
        try {
            return $this->createQueryBuilder('i')
                ->select('i.id')
                ->orderBy('i.id', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
            return 0;
        }
    }

    public function findWithCache($id): ?Item
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->useQueryCache(true)
            ->setResultCacheId(__METHOD__)
            ->setResultCacheLifetime(60)
            ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return Item[] Returns an array of Item objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

//    /*
//    public function findOneBySomeField($value): ?Item
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
//    */
}
