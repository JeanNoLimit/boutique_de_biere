<?php

namespace App\Repository;

use App\Model\Filters;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
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
    public function findByCriteria(Filters $filters)
    {

        $query = $this->createQueryBuilder('p');


        if (!empty($filters->searchProduct)) {
            $query
                ->andWhere('p.designation LIKE :searchProduct')
                ->setParameter('searchProduct', "%{$filters->searchProduct}%");
        }
        //     //Pour en savoir plus :
        //     // https://stackoverflow.com/questions/11704447/pass-array-of-conditions-to-doctrine-expr-orx-method
        // if (!empty($filters->providers)) {
        //     //On créé une instance de orX(). Cela représente une expression conditionnelle OR vide
        //     $orX = $query->expr()->orX();
        //     foreach ($filters->providers as $index => $provider) {
        //         //On ajoute des conditions individuelles à cette expression OR
        //         $orX->add($query->expr()->eq('p.provider', ':provider_' . $index));
        //         $query->setParameter('provider_' . $index, $provider);
        //     }
        //     $query->andWhere($orX);
        // }
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

        return $query->getQuery()->getResult();
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
