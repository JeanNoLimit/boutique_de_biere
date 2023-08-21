<?php

namespace App\Repository;

use App\Entity\BeerType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BeerType>
 *
 * @method BeerType|null find($id, $lockMode = null, $lockVersion = null)
 * @method BeerType|null findOneBy(array $criteria, array $orderBy = null)
 * @method BeerType[]    findAll()
 * @method BeerType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BeerTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BeerType::class);
    }

//    /**
//     * @return BeerType[] Returns an array of BeerType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BeerType
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
