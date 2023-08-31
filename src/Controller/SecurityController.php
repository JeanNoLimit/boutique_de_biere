<?php

namespace App\Controller;

use App\Form\CheckInformationsType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig',
            ['last_username' => $lastUsername,
             'error' => $error]);
    }

    // Cette fonction n'a pas besoin d'être modifiée. Le logout est géré automatiquement par symfony. Pour modifier la route de redirection voir dans security.yaml -> path:logout
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }



    /**
     * Fonction permettant la vérification de l'adresse de facturation avant de passer le panier en commande pour le paiment
     */ 
    #[Route('/check_Address', name: 'checkBillingAddress')]
    public function checkBillingAddress(Request $request, EntityManagerInterface $em, UserRepository $userRepo):Response
    {

        //On vérifie si l'utilisateur est connecté
        $this->denyAccessUnlessGranted('ROLE_USER');

        //On récupère le panier et l'utilisateur
        $session=$request->getSession();
        $panier = $session->get('panier', []);
        $idUser = $this->getUser()->getId();
        $user = $userRepo->find($idUser);
        if($user->isVerified()){
            if($panier !== []){
                //création du formulaire
                $form = $this->createForm(CheckInformationsType::class, $user);

                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                   
                    $user = $form->getData();

                    $em->persist($user);
                    $em->flush();
    
                    $this->addFlash('success', 'Informations de facturation validées');

                    return $this->redirectToRoute('add_order');
                }
            }else{
            $this->addFlash('alert', 'Votre panier est vide, impossible de passer commande!');

            return $this->redirectToRoute('cart_index');
           }
        }else{
            $this->addFlash('alert', 'Veuillez vérifier votre adresse mail avant de continuer!');

            return $this->redirectToRoute('cart_index');
        }

        
        return $this->render('security/check_address.html.twig', [
            'form' => $form
        ]);
    }
}
