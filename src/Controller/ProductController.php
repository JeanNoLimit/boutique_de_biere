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
}
