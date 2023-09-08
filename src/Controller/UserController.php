<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UpdateProfilType;
use App\Form\UpdatePasswordType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
    public function updateProfil(
        EntityManagerInterface $em,
        Request $request
        ): Response{

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

    #[Route('/user/update_profil_password', name: 'app_updatePassword')]
    public function updatePassword(
        EntityManagerInterface $em,
        Request $request,
        UserPasswordHasherInterface $hasher
        ): Response {
        
        $userID= $this->getUser()->getId();
        
        $user = $em->getRepository(User::class)->find($userID);

        $form = $this->createForm(UpdatePasswordType::class);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($hasher->isPasswordValid($user, $form->getData()["oldPlainPassword"])){
                $user->setPassword(
                    $hasher->hashPassword(
                        $user,
                        $form->getData()['newPlainPassword']
                    )
                );

                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Votre mot de passe a bien été modifié');
                return $this->redirectToRoute('app_userProfile');
            }else{
                $this->addFlash('alert', 'Mot de passe érroné');
            }

        }
        return $this->render('user/update_password.html.twig', [
            'form' =>$form,
        ]);

    }

}
