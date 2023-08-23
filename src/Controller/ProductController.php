<?php

namespace App\Controller;

use App\Entity\Product;
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


        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }
}
