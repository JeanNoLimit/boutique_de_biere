<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\CartType;
use App\Model\Filters;
use App\Entity\Product;
use App\Form\ReviewType;
use App\Form\FiltersType;
use App\Entity\ShopParameters;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    // Affichage de la liste des produits
    #[Route('/products', name: 'products_index')]
    public function index(
        EntityManagerInterface $entityManager,
        Request $request,
    ): Response {

        $parameters = $entityManager->getRepository(ShopParameters::class)->findAll()[0];

        $filters = new Filters();
        $filters->page = $request->query->get('page', 1);
        $formFilter = $this ->createForm(FiltersType::class, $filters);

        $formFilter->handleRequest($request);
        $products = $entityManager->getRepository(Product::class)->findByCriteria($filters);

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'parameters' => $parameters,
            'formFilter' => $formFilter,
        ]);
    }


    // Affichage d'un produit
    #[Route('/products/{slug}', name: 'detail_product')]
    public function show(
        EntityManagerInterface $entityManager,
        string $slug = null,
        Request $request
    ): Response {

        $page = $request->query->get('page', 1);
        $product = $entityManager->getRepository(Product::class)->findOneBySlug($slug);
        $reviews = $entityManager->getRepository(Review::class)->findByProduct($product, $page);
        $parameters = $entityManager->getRepository(ShopParameters::class)->findAll()[0];
        //On veut savoir si l'utilisateur a déjà donné un avis sur ce produit.
        $userReview = $entityManager->getRepository(Review::class)->findReviewIfExist($this->getUser(),$product);

        $form = $this->createForm(CartType::class);
        $form->handleRequest($request);
        // Pour info : 
        // J'ai basculé la gestion du formulaire d'ajout du produit dans le panier dans le cartController
       
        return $this->render('product/detail.html.twig', [
            'product' => $product,
            'parameters' => $parameters,
            'reviews' => $reviews,
            'userReview' => $userReview,
            'form' => $form
        ]);
    }


    //Ajout d'une review + modification.
    #[IsGranted('ROLE_USER')]
    #[Route('/products/add_review/{slug}', name: 'add_review')]
    #[Route('/products/edit_review/{slug}/{userId}/{id}', name: 'edit_review')]
    public function add_review(   
        EntityManagerInterface $entityManager,
        Review $review = null,
        Request $request,
        string $slug = null,
        $userId=Null,
    ): Response {

        //On récupère le produit et l'utilisateur nécessaire pour créer la review. 
        $product = $entityManager->getRepository(Product::class)->findOneBySlug($slug);
        $user = $this->getUser();

        if(!$review || $userId!=$user->getId()){
            $review = new Review();
            //On insère le user et le produit dans la nouvel instance de review.
            $review->setUser($user);
            $review->setProduct($product);
        }else{
            $review->setUpdatedAt(new \DateTimeImmutable());
        }

        $formReview = $this->createForm(ReviewType::class, $review);
        $formReview->handleRequest($request);

        if($formReview->isSubmitted() && $formReview->isValid()) {
           
            $review=$formReview->getData();
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('detail_product', ['slug' => $product->getSlug()]);
        }
    
        return $this->render('product/add_review.html.twig', [
            'formReview' => $formReview,
            'product' => $product
        ]);
    }
}
