<?php

namespace App\Repository;

use App\Model\Filters;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Product::class);
        $this->paginator = $paginator;
    }


    //Cherche les produits en fonction des critères renseignés dnas le formulaire produits
    public function findByCriteria(Filters $filters): PaginationInterface
    {
        //On cherche à récupérer les produits et la moyenne de leur note si celle-ci existe
        $query = $this->createQueryBuilder('p')
            ->select('p', 'AVG(r.rating) AS averageRating')
            ->leftjoin('p.reviews', 'r')
            ->groupBy('p.id');

        if (!empty($filters->searchProduct)) {
            $query
                ->andWhere('p.designation LIKE :searchProduct')
                ->setParameter('searchProduct', "%{$filters->searchProduct}%");
        }

        if (!empty($filters->providers)) {
            $query
                ->select('pr', 'p', 'AVG(r.rating) AS averageRating')
                ->join('p.provider', 'pr')
                ->andWhere('pr.id IN (:provider)')
                ->setParameter('provider', $filters->providers);
        }

        if (!empty($filters->beerTypes)) {
            $query
                ->select('bt', 'p', 'AVG(r.rating) AS averageRating')
                ->join('p.beerTypes', 'bt')
                ->andWhere('bt.id IN (:beerTypes)')
                ->setParameter('beerTypes', $filters->beerTypes)
                ->groupBy('p.id', 'bt.id');
        }

        if (!empty($filters->min)) {
            $query = $query
                ->andWhere('p.price >= :min')
                ->setParameter('min', $filters->min);
        }

        if (!empty($filters->max)) {
            $query = $query
                ->andWhere('p.price <= :max')
                ->setParameter('max', $filters->max);
        }
        if (!empty($filters->tauxMin)) {
            $query = $query
                ->andWhere('p.alcoholLevel >= :tauxMin')
                ->setParameter('tauxMin', $filters->tauxMin);
        }
        if (!empty($filters->tauxMax)) {
            $query = $query
                ->andWhere('p.alcoholLevel <= :tauxMax')
                ->setParameter('tauxMax', $filters->tauxMax);
        }

        $query = $query->getQuery();

          return $this->paginator->paginate(
              $query,
              $filters->page,
              12
          );
    }


//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
