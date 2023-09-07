<?php

namespace App\Controller;

use App\Form\UpdateProfilType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_userProfile')]
    public function index(OrderRepository $orderRepository): Response
    {
        $userId = $this->getUser()->getId();

        $ordersArchives = $orderRepository->findOrdersPreparedByUserId($userId);
        $ordersInProcess = $orderRepository->findOrdersInProccessByUserId($userId);

        return $this->render('user/view_profile.html.twig', [
            'ordersInProcess' => $ordersInProcess,
            'ordersArchives' => $ordersArchives,
        ]);
    }


    #[Route('/user/update_profil', name: 'app_updateProfile')]
    public function updateProfil(EntityManagerInterface $em, Request $request): Response
    {

        $user= $this->getUser();

        $form = $this->createForm(UpdateProfilType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre profil a été modifié');

        }

        return $this->render('user/update_profile.html.twig', [
            'form' =>$form,
        ]);
    }
}
