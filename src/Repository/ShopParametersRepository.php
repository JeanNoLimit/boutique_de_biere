<?php

namespace App\Repository;

use App\Entity\ShopParameters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShopParameters>
 *
 * @method ShopParameters|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShopParameters|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShopParameters[]    findAll()
 * @method ShopParameters[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopParametersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShopParameters::class);
    }

//    /**
//     * @return ShopParameters[] Returns an array of ShopParameters objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ShopParameters
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
