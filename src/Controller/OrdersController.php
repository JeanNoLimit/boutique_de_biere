<?php

namespace App\Controller;

use DateTime;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrdersController extends AbstractController
{
    #[Route('/add_order', name: 'add_order')]
    public function index(Request $request, ProductRepository $productRepository, EntityManagerInterface $em): Response
    {
        //On vérifie si l'utilisateur est connecté
        $this->denyAccessUnlessGranted('ROLE_USER');

        //On récupère le panier et l'utilisateur
        $session=$request->getSession();
        $panier = $session->get('panier', []);
        $user = $this->getUser();
        

        if($user->isVerified()){
           if($panier !== []){

                //Création objet commande et insertion des données
                $order = new Order();
                //Va nous servir à créer la référence (id de l'utilisateur + date, heure, minute)
                $time=new \DateTimeImmutable();
                $reference = $user->getId().$time->format('Ymdhis');

                $order->setUser($user);
                $order->setReference($reference);

                //Création du détail de commande insertion des données
                foreach($panier as $id => $qte) {
                    $orderDetails = new OrderDetails();
                    //On récupère le produit
                    $product = $productRepository->find($id);
                    if (!$product) {
                        throw $this->createNotFoundException(
                            'No product found for id '.$id
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
                $em->persist($order);
                $em->flush();

                $session->remove('panier');

                $this->addFlash('success', 'Votre commande a été créée');


           }else{
            $this->addFlash('alert', 'Votre panier est vide, impossible de passer commande!');

            return $this->redirectToRoute('cart_index');
           }
        }else{
            $this->addFlash('alert', 'Veuillez vérifier votre adresse mail avant de continuer!');

            return $this->redirectToRoute('cart_index');
        }

        return $this->render('orders/index.html.twig', [
            // 'order' => $order
        ]);
    }

    

}
