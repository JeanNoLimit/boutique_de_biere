<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_userProfile')]
    public function index(OrderRepository $orderRepository): Response
    {
        $userId=$this->getUser()->getId();

        $ordersArchives = $orderRepository->findOrdersPreparedByUserId($userId);
        $ordersInProcess = $orderRepository->findOrdersInProccessByUserId($userId);

        return $this->render('user/view_profile.html.twig', [
            'ordersInProcess' => $ordersInProcess,
            'ordersArchives' => $ordersArchives,
        ]);
    }


    #[Route('/user/update_profil', name: 'app_updateProfile')]
    public function update_profil(): Response
    {



        return $this->render('user/update_profile.html.twig', [
        ]);
    }

}
