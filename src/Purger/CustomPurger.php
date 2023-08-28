<?php

namespace App\Purger;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\DataFixtures\Purger\PurgerInterface;

/**
 * Cette class permet de selectionner les classes à purger lors du chargement des fixtures. 
 * Il faut lancer la commande suivant dans le terminal : php bin/console doctrine:fixtures:load --purger=my_purger
 * Documentation : https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html#specifying-purging-behavior
 */
class CustomPurger implements PurgerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function purge() : void
    {
        $classesToClear = [
            App\Entity\Product::class,
            App\entity\Provider::class,
            App\entity\User::class
        ];

        foreach ($classesToClear as $class) {
            $repository = $this->entityManager->getRepository($class);
            $entities = $repository->findAll();

            foreach ($entities as $entity) {
                $this->entityManager->remove($entity);
            }
        }

        $this->entityManager->flush();
    }
}