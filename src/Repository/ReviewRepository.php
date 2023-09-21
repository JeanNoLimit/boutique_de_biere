<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Review>
 *
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Review::class);
        $this->paginator = $paginator;
    }

    public function findByProduct($product, $page): PaginationInterface
    {
        $query = $this->createQueryBuilder('r')
            ->andWhere('r.product= :product')
            ->orderBy('r.createdAt', 'DESC')
            ->setParameter('product', $product)
            ->getQuery();

        return $this->paginator->paginate(
            $query,
            $page,
            3
        );
    }


    /**
     * Récupère une review si un utilisateur en a déjà renseigné une sur un produit
     *
     * @param User $user
     * @param Product $product
     * @return Review|null
     */
    public function findReviewIfExist($user, $product): ?Review
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user= :user')
            ->andWhere('r.product= :product')
            ->setParameter('user', $user)
            ->setParameter('product', $product)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

//    /**
//     * @return Review[] Returns an array of Review objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Review
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
