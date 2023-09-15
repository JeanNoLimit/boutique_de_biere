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
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Product::class);
        $this->paginator = $paginator;
    }

    // Cherche les derniers porduits enregistrés pour l'affichage sur la page d'accueil
    public function findNewProducts(int $limit)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.available = true')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    //Cherche les produits en fonction des critères renseignés dnas le formulaire produits
    public function findByCriteria(Filters $filters): PaginationInterface
    {

        $query = $this->createQueryBuilder('p');


        if (!empty($filters->searchProduct)) {
            $query
                ->andWhere('p.designation LIKE :searchProduct')
                ->setParameter('searchProduct', "%{$filters->searchProduct}%");
        }

        if (!empty($filters->providers)) {
            $query
                ->select('pr', 'p')
                ->join('p.provider', 'pr')
                ->andWhere('pr.id IN (:provider)')
                ->setParameter('provider', $filters->providers);
        }

        if (!empty($filters->beerTypes)) {
            $query
                ->select('bt', 'p')
                ->join('p.beerTypes', 'bt')
                ->andWhere('bt.id IN (:beerTypes)')
                ->setParameter('beerTypes', $filters->beerTypes);
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
              6
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
