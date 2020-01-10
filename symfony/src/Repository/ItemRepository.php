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

    /**
     * Use this function if you want to avoid using a second level cache for some reason
     *
     * @param $id
     * @return Item|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findWithCache($id): ?Item
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->useQueryCache(true)
            ->enableResultCache(60, 'Item_'.$id)
            ->getOneOrNullResult();
    }

    /**
     * Use this function if you want to avoid using a second level cache for some reason
     *
     * @param array $criteriaArr
     * @param array $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findWithCacheBy(array $criteriaArr = [], array $orderBy = [], int $limit = null, int $offset = null): array
    {
        $query = $this->createQueryBuilder('i');
        foreach ($criteriaArr as $fieldName => $criteria){
            $query->andWhere("i.$fieldName = :$fieldName")
                ->setParameter("$fieldName", $criteria);
        }
        foreach ($orderBy as $fieldName => $ascDesc){
            $query->addOrderBy("i.$fieldName", $ascDesc);
        }
        if (!is_null($offset)){
            $query->setFirstResult($offset);
        }

        $query->setMaxResults($limit);
//        $sql = $query->getDQL();
        return $query->getQuery()
            ->useQueryCache(true)
            ->enableResultCache(60)
//            ->enableResultCache(60, md5($sql))
            ->getResult();
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
