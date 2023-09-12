<?php

namespace App\Controller;

use App\Form\CartType;
use App\Model\Filters;
use App\Entity\Product;
use App\Form\FiltersType;
use App\Entity\ShopParameters;
use App\Repository\BeerTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    // Affichage de la liste des produits
    #[Route('/products', name: 'products_index')]
    public function index(EntityManagerInterface $entityManager, Request $request, BeerTypeRepository $btr): Response
    {


        $parameters = $entityManager->getRepository(ShopParameters::class)->findAll()[0];

        $filters = new Filters();
        $formFilter = $this ->createForm(FiltersType::class, $filters);

        $formFilter->handleRequest($request);



        if ($formFilter->isSubmitted() && $formFilter->isValid()) {
            $products = $entityManager->getRepository(Product::class)->findByCriteria($filters);
        } else {
            $products = $entityManager->getRepository(Product::class)->findAll();
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'parameters' => $parameters,
            'formFilter' => $formFilter,
        ]);
    }


    // Affichage d'un produit
    #[Route('/products/{slug}', name: 'detail_product')]
    public function show(EntityManagerInterface $entityManager, string $slug = null, Request $request): Response
    {
        $product = $entityManager->getRepository(Product::class)->findOneBySlug($slug);
        $parameters = $entityManager->getRepository(ShopParameters::class)->findAll()[0];

        $form = $this->createForm(CartType::class);
        // $form->setData(['idProduct' => $product->getId()]);
        $form->handleRequest($request);


        // J'ai basculé la gestion du formulaire dans le cartController
        // if ($form->isSubmitted() && $form->isValid()) {
        //     // dd($form);
        //     // Je récupère la quantité de produit renseigné et je récupère l'id du produit
        //     $quantity = $form->getData()['quantity'];
        //     $productId = $product->getId();

        //     // Solution 1 :
        //     // return new RedirectResponse($this->generateUrl('cart_add',
                    //  ['id' => $productId, 'quantity' => $quantity]));
        //     // Solution 2 :
        //     $response = $this->forward('App\Controller\CartController::add', [
        //         'id' => $productId,
        //         'quantity' => $quantity
        //     ]);

        //     return $response;
        // }


        return $this->render('product/detail.html.twig', [
            'product' => $product,
            'parameters' => $parameters,
            'form' => $form
        ]);
    }
}
