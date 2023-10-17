<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\MembershipContributionService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    public function __construct()
    {
        Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
    }

    #[Route('/stripe/checkout', name: 'app_stripe_checkout')]
    public function startPayement(
        Request $request,
        EntityManagerInterface $em,
        MembershipContributionService $contributionService
    ): RedirectResponse {

        //On veut récupérer la liste des produits
        $productStripe = [];

        // On récupère le panier et la cotisation
        $session = $request->getSession();
        $panier = $session->get('panier', []);
        $cotisation = $contributionService->checkContribution();
        //Si le panier est vide on renvoie vers le panier et affichage d'un message d'un erreur
        if (!$panier) {
            $this->addFlash('alert', 'Votre panier est vide, impossible de passer commande!');
            return $this->redirectToRoute('cart_index');
        }

        // On récupère la liste des produits du panier
        foreach ($panier as $id => $quantity) {
            $product = $em->getRepository(Product::class)->findOneById($id);

            $productStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                    'name' => $product->getDesignation()
                    ],
                ],
                'quantity' => $quantity,
            ];
        }
        //On ajoute la cotisation à la liste des produits
        if ($cotisation['price'] > 0) {
            $productStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $cotisation['price'],
                    'product_data' => [
                    'name' => 'Cotisation valable jusqu\'au ' . $cotisation['endDate']->format('d-m-Y')
                    ],
                ],
                'quantity' => 1,
            ];
        }
        $checkout_session = \Stripe\Checkout\Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'line_items' => [[
                $productStripe
            ]],
            'mode' => 'payment',
            //aide construction lien:
            //https://stackoverflow.com/questions/67555522/symfony-5-stripe-v3-cant-find-checkout-session-id
            'success_url' => $this
                    ->generateUrl('add_order', [], UrlGeneratorInterface::ABSOLUTE_URL)
                    . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $this->generateUrl('stripe_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
          ]);


          return new RedirectResponse($checkout_session->url);
    }

    // Pour la phase de test, l'utilisateur est redirigé vers la page success depuis la fonction add_order.
    // On concidère que la commande est validée dès que stripe nous redirige vers le site, étant donné qu'il
    // difficile d'utiliser les webHooks.
    // #[Route('/stripe/success', name: 'stripe_success')]
    // public function stripeSucces(): Response
    // {


    //     return $this->render('orders/success.html.twig',[

    //     ]);
    // }

    #[Route('/stripe/cancel', name: 'stripe_cancel')]
    public function stripeCancel(): Response
    {
        return $this->render('orders/cancel.html.twig', [

        ]);
    }
}
