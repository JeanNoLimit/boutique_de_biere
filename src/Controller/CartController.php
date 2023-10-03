<?php

namespace App\Controller;

use App\Service\MembershipContributionService;
use App\Form\CartType;
use App\Entity\Product;
use App\Form\UpdateProfilType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /***************************** GESTION VUE PANIER *****************************************/

    #[Route('/cart', name: 'cart_index')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        MembershipContributionService $membershipContribution
    ): Response{

        $session = $request->getSession();
        $panier = $session->get('panier', []);
        
        $SsTotal = null;
        $stockTemp = null;
        $total = null;
        $elements = [];
        
        //On récupère le panier avec les produits....
        foreach ($panier as $id => $quantity) {
            $product = $entityManager->getRepository(Product::class)->findOneById($id);
            $SsTotal = $product->getPrice() * $quantity;
            $stockTemp = $product->getStock() - $quantity;
            $elements[] = [
                'product' => $product,
                'quantity' => $quantity,
                'stockTemp' => $stockTemp,
                'SsTotal' => $SsTotal
            ];

            $total += $SsTotal;
        }

        /****** COTISATION ******/
        $cotisation=$membershipContribution->checkContribution();
        $total += $cotisation['price'];
        
        return $this->render('cart/index.html.twig', [
            'panier' => $panier,
            'elements' => $elements,
            'cotisation' => $cotisation,
            'total' => $total
        ]);
    }

    /************************ Fonction gestion du formulaire ajout au panier - VUE DETAIL PRODUIT************************/

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(int $id = null, int $quantity = null, Request $request, EntityManagerInterface $em): Response
    {
        // On récupère les données du formulaire.
        $form = $this->createForm(CartType::class);
        $form->handleRequest($request);

        // On récupère le slug du produit pour rediriger vers la page du produit
        // après l'ajout de ce dernier dans le panier.
        $product = $em->getRepository(Product::class)->findOneById($id);
        $slug = $product->getSlug();

        // On récupère la session
        $session = $request->getSession();

        // On récupère le panier. si on entre dans la boutique,
        // le panier n'existe pas, on en récupère un en créant un tableau vide
        $panier = $session->get('panier', []);


        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère la quantité soumise dans le formulaire // dd($quantity = $form->getData()['quantity']);
            $quantity = $form->getData()['quantity'];
            // On ajoute la quantité du produit au panier

            if (!empty($panier[$id])) {
                $panier[$id] = $panier[$id] + $quantity;
            } else {
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
    public function decrementProduct(Request $request, int $id): Response
    {
        $panier = $request->getSession()->get('panier', []);

        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            }
        }
        $request->getSession()->set('panier', $panier);

        return $this->redirectToRoute('cart_index');
    }


    #[Route('/cart/increment/{id}', name: 'increment_product_cart')]
    public function incrementProduct(Request $request, int $id): Response
    {
        $panier = $request->getSession()->get('panier', []);

        if (!empty($panier[$id])) {
            if ($panier[$id] >= 1) {
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


    /************************************ VERIFICATION ADRESSE DE FACTURATION AVANT VALIDATION COMMANDE *********************************/

    #[Route('/cart/check_Address', name: 'checkBillingAddress')]
    #[IsGranted('ROLE_USER')]
    public function checkBillingAddress(
        Request $request,
        EntityManagerInterface $em,
        UserRepository $userRepo,
        MembershipContributionService $membershipContribution,
    ): Response {

        //On vérifie si l'utilisateur est connecté
        // $this->denyAccessUnlessGranted('ROLE_USER');

        //On récupère le panier et la cotisation de l'utilisateur
        $session = $request->getSession();
        $panier = $session->get('panier', []);
        $SsTotal = null;
        $total = null;
        $stockTemp = null;
        $elements = [];
        $cotisation = $membershipContribution->checkContribution();
        $idUser = $this->getUser()->getId();
        $user = $userRepo->find($idUser);

        //On vérifie que l'utilisateur a bien validé son adresse email
        if ($user->isVerified()) {
            if ($panier !== []) {

                /********** Récupération des informations du panier ***********/
                foreach ($panier as $id => $quantity) {
                    $product = $em->getRepository(Product::class)->findOneById($id);
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

                $total += $cotisation['price'];

                /******* création du formulaire *************/
                $form = $this->createForm(UpdateProfilType::class, $user);

                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $user = $form->getData();

                    $em->persist($user);
                    $em->flush();

                    // $this->addFlash('success', 'Informations de facturation validées');

                    // Gestion de la redirection vers le site de paiement
                    return $this->redirectToRoute('app_stripe_checkout');
                }
            } else {
                $this->addFlash('alert', 'Votre panier est vide, impossible de passer commande!');

                return $this->redirectToRoute('cart_index');
            }
        } else {
            $this->addFlash('alert', 'Veuillez vérifier votre adresse mail avant de continuer!');

            return $this->redirectToRoute('cart_index');
        }


        return $this->render('cart/check_address.html.twig', [
            'elements' => $elements,
            'total' => $total,
            'form' => $form,
            'cotisation' => $cotisation
        ]);
    }
}
