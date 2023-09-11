<?php

namespace App\Controller;

use App\Entity\ShopParameters;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager, ProductRepository $productRepository): Response
    {
        $newProducts = $productRepository->findNewProducts(4);
        $parameters = $entityManager->getRepository(ShopParameters::class)->findAll()[0];

        return $this->render('home/index.html.twig', [
            'products' => $newProducts,
            'parameters' => $parameters
        ]);
    }
}
