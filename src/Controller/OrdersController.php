<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Order;
use Stripe\StripeClient;
use Stripe\PaymentIntent;
use App\Entity\OrderDetails;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\MembershipContributionService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrdersController extends AbstractController
{
    #[Route('/order/add_order', name: 'add_order')]
    public function index(
        Request $request,
        ProductRepository $productRepository,
        EntityManagerInterface $em,
        MembershipContributionService $MCS
    ): Response {

        // On vérifie que l'utilisateur a bien payé sa commande sur Stripe avant de valider sa commande
        $stripe = new StripeClient($_ENV["STRIPE_SECRET"]);
        $session_id = $request->query->get('session_id');
        $session_stripe = $stripe->checkout->sessions->retrieve($_GET['session_id']);
        //On vérifie si le ID stripe existe déjà dans une entité commande
        $orderVerification = $em
            ->getRepository(Order::class)
            ->findOneBy(["stripeId" => $session_stripe["payment_intent"]]);

        try {
            if (!empty($session_id)) {
                $session_stripe = $stripe->checkout->sessions->retrieve($_GET['session_id']);

                //Si la session stripe n'est pas retrouvé, renvoie vers une erreur 500.
                //IL faudra régler ce problème avec une page erreur 500 personnalisée.
            }
        } catch (\Error $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }


        //On récupère le panier, la cotisation et l'utilisateur
        $cotisation = $MCS->checkContribution();
        $session = $request->getSession();
        $panier = $session->get('panier', []);
        $userSession = $this->getUser();
        $user = $em->getRepository(User::class)->find($userSession->getId());

        if (!$orderVerification) {
            if ($user->isVerified()) {
                if ($panier !== []) {
                    //Création objet commande et insertion des données
                    $order = new Order();
                    //Va nous servir à créer la référence (id de l'utilisateur + date, heure, minute)
                    $time = new \DateTimeImmutable();
                    $reference = $user->getId() . $time->format('Ymdhis');

                    $order->setUser($user);
                    $order->setReference($reference);
                    $order->setStripeId($session_stripe["payment_intent"]);
                    // /!\ Attention, la validation du paiement est automatisé pour les tests en local,
                    // mais de devra être supprimé lorsque les webhhooks seront fonctionnels.
                    $order->setIsPaid(true);

                    //Création du détail de commande insertion des données
                    foreach ($panier as $id => $qte) {
                        $orderDetails = new OrderDetails();
                        //On récupère le produit
                        $product = $productRepository->find($id);
                        if (!$product) {
                            throw $this->createNotFoundException(
                                'No product found for id ' . $id
                            );
                        }

                        $price = $product->getPrice();

                        //On remplit orderDetails
                        $orderDetails->setProduct($product);
                        $orderDetails->setPrice($price);
                        $orderDetails->setQuantity($qte);
                        //On rajoute OrderDetails à la commande
                        $order->addOrderDetail($orderDetails);
                        //On retire du stock la quantité commandé
                        $newStock = $product->getStock() - $qte;
                        $product->setStock($newStock);
                    }

                    //Ajout cotisation à la commande et mise à jour nouvelle date à l'utilisateur
                    $endDate = $user->getMembershipContributionEndDate();
                    if (!isset($endDate) || $cotisation['endDate']->format('Y-m-d') != $endDate->format('Y-m-d')) {
                        $order->setContribution($cotisation['price']);
                        $user->setMembershipContributionEndDate($cotisation['endDate']);
                    }

                    $em->persist($order);
                    $em->flush();

                    $session->remove('panier');
                } else {
                    $this->addFlash('alert', 'Votre panier est vide, impossible de passer commande!');

                    return $this->redirectToRoute('cart_index');
                }
            } else {
                $this->addFlash('alert', 'Veuillez vérifier votre adresse mail avant de continuer!');

                return $this->redirectToRoute('cart_index');
            }
        } else {
            $this->addFlash('alert', 'Erreur lors du traitement de la commande! celle-ci existe déjà!');

            return $this->redirectToRoute('cart_index');
        }


        return $this->render('orders/success.html.twig', [
            'reference' => $reference
        ]);
    }


    #[Route('/order/order_detail/{reference}', name: 'order_detail')]
    public function show(
        OrderRepository $orderRepository,
        string $reference = null
    ): Response {

            $order = $orderRepository->findOrderByReference($reference);
            $user = $this->getUser();
            $SsTotal = null;
            $total = null;
            $elements = [];

        if (!empty($order)) {
            if ($user == $order->getUser()) {
                $orderDetails = $order->getOrderDetails();
                foreach ($orderDetails as $orderDetail) {
                    $product = $orderDetail->getProduct();
                    $quantity = $orderDetail->getQuantity();
                    $price = $orderDetail->getPrice();
                    $SsTotal = $price * $quantity;

                    $elements[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'price' => $price,
                        'SsTotal' => $SsTotal
                    ];

                    $total += $SsTotal ;
                }
                $contribution = $order->getContribution();
                if (isset($contribution)) {
                    $total += $order->getContribution();
                }
            } else {
                $this->addFlash('alert', 'Vous ne pouvez pas accéder à cette commande');

                return $this->redirectToRoute('app_home');
            }
        } else {
            $this->addFlash('alert', 'Cette commande n\'existe pas!');

            return $this->redirectToRoute('app_home');
        }


        return $this->render('orders/detail.html.twig', [
           'order' => $order,
           'elements' => $elements,
           'total' => $total
        ]);
    }
}
