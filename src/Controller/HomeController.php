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
        // $newProducts = $productRepository->findNewProducts(4);
        $newProducts = $productRepository->findBy([], ['createdAt' => 'DESC'], 4);
        $parameters = $entityManager->getRepository(ShopParameters::class)->findAll()[0];

        return $this->render('home/index.html.twig', [
            'products' => $newProducts,
            'parameters' => $parameters
        ]);
    }


    #[Route('/cgv', name: 'app_cgv')]
    public function cgv(): Response
    {
        return $this->render('home/cgv.html.twig', []);
    }

    #[Route('/cgu', name: 'app_cgu')]
    public function cgu(): Response
    {
        return $this->render('home/cgu.html.twig', []);
    }

    #[Route('/rgpd_lechoppe', name: 'app_rgpd')]
    public function rgpd(): Response
    {
        return $this->render('home/rgpd.html.twig', []);
    }

    #[Route('/sitemap', name: 'app_sitemap')]
    public function sitemap(): Response
    {
        return $this->render('home/sitemap.html.twig', []);
    }

    #[Route('/mentions_legales', name: 'app_mentions')]
    public function legalNotices(EntityManagerInterface $entityManager): Response
    {
        $parameters = $entityManager->getRepository(ShopParameters::class)->findAll()[0];

        return $this->render('home/legal_notices.html.twig', ['parameters' => $parameters]);
    }

    #[Route('/presentation', name: 'app_presentation')]
    public function presentation(EntityManagerInterface $entityManager): Response
    {
        $parameters = $entityManager->getRepository(ShopParameters::class)->findAll()[0];

        return $this->render('home/presentation.html.twig', ['parameters' => $parameters]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
      
        return $this->render('home/contact.html.twig', []);
    }



}
