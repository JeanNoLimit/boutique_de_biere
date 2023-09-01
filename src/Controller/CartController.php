<?php

namespace App\Controller;


use App\Form\CartType;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
// use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{

    /************* GESTION VUE PANIER ************/
    #[Route('/cart', name: 'cart_index')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session=$request->getSession();
        $panier = $session->get('panier', []);
        $SsTotal=null;
        $stockTemp=null;
        $total=null;
        $elements=[];

        foreach ($panier as $id => $quantity) 
        {
            $product = $entityManager->getRepository(Product::class)->findOneById($id);
            $SsTotal = $product->getPrice() * $quantity;
            $stockTemp = $product->getStock() - $quantity;
            $elements[] = [
                'product' => $product,
                'quantity' => $quantity,
                'stockTemp' => $stockTemp,
                'SsTotal' => $SsTotal
            ];

            $total += $SsTotal ;
        }

        return $this->render('cart/index.html.twig', [
            'panier' => $panier,
            'elements' => $elements,
            'total' => $total
        ]);
    }

    /************************ Fonction gestion du formulaire ajout vue détail produit************************/
    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(int $id = null, int $quantity=null, Request $request, EntityManagerInterface $em): Response
    {
        // On récupère les données du formulaire.
        $form = $this->createForm(CartType::class);
        $form->handleRequest($request);

        // On récupère le slug du produit pour rediriger vers la page du produit après l'ajout de ce dernier dans le panier.
        $product=$em->getRepository(Product::class)->findOneById($id);
        $slug = $product->getSlug();
        
        // On récupère la session
        $session = $request->getSession();

        // On récupère le panier. si on entre dans la boutique, le panier n'existe pas, on en récupère un en créant un tableau vide
        $panier = $session->get('panier', []);


        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère la quantité soumise dans le formulaire // dd($quantity = $form->getData()['quantity']);
            $quantity = $form->getData()['quantity'];
            // On ajoute la quantité du produit au panier

                if(!empty($panier[$id])) {
                    $panier[$id] = $panier[$id] + $quantity;
                }
                else {
                    $panier[$id] = $quantity;
                }     
        }
        // On sauvegarde le panier en session pour continuer nos achats en boutique
        $session->set('panier', $panier);

        // dd($session->get('panier'));
        
        return new RedirectResponse($this->generateUrl('detail_product', ['slug' => $slug]));
    }



    /*************************************** FONCTIONS DU PANIER ************************************/

    #[Route('/cart/delete/{id}', name: 'delete_product_cart')]
    public function deleteProduct(Request $request, int $id) 
    {
        $panier = $request->getSession()->get('panier', []);
        unset($panier[$id]);
        $request->getSession()->set('panier', $panier);

        return $this->redirectToRoute('cart_index');
    }


    #[Route('/cart/decrement/{id}', name: 'decrement_product_cart')]
    public function decrementProduct(Request $request, int $id) : Response
    {
        $panier = $request->getSession()->get('panier', []);
        
        if(!empty($panier[$id])){
            if($panier[$id] > 1) {
                $panier[$id]--;
            }
        }
        $request->getSession()->set('panier', $panier);

        return $this->redirectToRoute('cart_index');
    }


    #[Route('/cart/increment/{id}', name: 'increment_product_cart')]
    public function incrementProduct(Request $request, int $id) : Response
    {
        $panier = $request->getSession()->get('panier', []);
        
        if(!empty($panier[$id])){
            if($panier[$id] >= 1) {
                $panier[$id]++;
            }
        }
        $request->getSession()->set('panier', $panier);

        return $this->redirectToRoute('cart_index');
    }

    
    #[Route('/cart/delete_cart', name: 'delete_cart')]
    public function deleteCart(Request $request) 
    {
        $request->getSession()-> remove('panier');

        return $this->redirectToRoute('cart_index');

    }

}
