<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Review;
use App\Entity\Product;
use App\Entity\BeerType;
use App\Entity\Provider;
use App\Entity\ProductionType;
use App\Entity\ShopParameters;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ShopParametersRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    private ShopParametersRepository $ShopParametersRepository;

    public function __construct(ShopParametersRepository $ShopParametersRepository)
    {
        $this->ShopParametersRepository = $ShopParametersRepository;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $logo = $this->ShopParametersRepository->findAll()[0];

        return $this->render('admin/dashboard.html.twig', [
            'logo' => $logo,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('L\'Echoppe - Administration')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {

        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToCrud('Gestion des commandes', 'fa-solid fa-sack-dollar', Order::class),
            MenuItem::linkToCrud('Liste des Utilisateurs', 'fa-solid fa-users', User::class),
            MenuItem::linkToCrud('Modération des commentaires', 'fa-solid fa-ban', Review::class),
            MenuItem::subMenu('Catalogues', 'fa fa-book')->setSubItems([
                MenuItem::linkToCrud('Brasseries', 'fa fa-handshake', Provider::class),
                MenuItem::linkToCrud('Bières', 'fa fa-beer-mug-empty', Product::class)
            ]),
            MenuItem::linkToCrud('Type de production', 'fa fa-star', ProductionType::class),
            MenuItem::linkToCrud('Type de bière', 'fa fa-wheat-awn', BeerType::class),
            MenuItem::linkToCrud('Paramètres du site', 'fa fa-screwdriver-wrench', ShopParameters::class)

        ];
    }
}
