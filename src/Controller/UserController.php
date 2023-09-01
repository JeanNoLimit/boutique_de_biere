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
        $userID=$this->getUser()->getId();




        return $this->render('user/view_profile.html.twig', [
        ]);
    }


    #[Route('/user/update_profil', name: 'app_updateProfile')]
    public function update_profil(): Response
    {



        return $this->render('user/update_profile.html.twig', [
        ]);
    }

}
