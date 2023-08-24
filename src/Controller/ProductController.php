<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ShopParameters;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    // Affichage de la liste des produits
    #[Route('/products', name: 'app_product')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        
        $products = $entityManager->getRepository(Product::class)->findAll();
        $parameters = $entityManager->getRepository(ShopParameters::class)->findAll()[0];

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'parameters' => $parameters
        ]);
    }

    // Affichage d'un produit
    #[Route('/products/{slug}', name: 'detail_product', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager, string $slug=null): Response
    {

        $product=$entityManager->getRepository(Product::class)->findOneBySlug($slug);
        $parameters = $entityManager->getRepository(ShopParameters::class)->findAll()[0];

        return $this->render('product/detail.html.twig', [
            'product' => $product,
            'paramters' => $parameters
        ]);
    }

}
