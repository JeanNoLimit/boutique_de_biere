<?php 

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

use Twig\Environment;
use App\Entity\Invoice;
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\ShopParametersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * 
 * This class use to save invoices on serveur
 **/
class PdfGenerator {

    public function __construct(
        private Security $security,
        private RequestStack $requestStack,
        private UserRepository $userRepository,
        private OrderRepository $orderRepository,
        private ShopParametersRepository $shopParametersRepository,
        private Environment $twig,
        private EntityManagerInterface $em
    ) {
        $this->requestStack = $requestStack;
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepository;
        $this->security = $security;
        $this->shopParametersRepository = $shopParametersRepository;
        $this->twig = $twig;
        $this->em = $em;
    }


    /**
     * Sauvegarde de la facture au format PDF moment de la création de la commande.
     * On créé également une nouvelle instance de facture pour récupérer le fichier dans le B.O.
     *
     * @param string|null $reference
     * @return void
     */
    public function saveInvoice(
        string $reference= null,
    )  {

        $dompdf = new Dompdf();
        $data = [];
        $order = $this->orderRepository->findOneByReference($reference);
        $userSession = $this->security->getUser();
        $user = $this->userRepository->find($userSession);
        $SsTotal = null;
        $total = null;
        $elements = [];

        if (!empty($order)) {
            if ($userSession == $order->getUser()) {
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

               

                $data = [
                    'user' => $user,
                    'order' => $order,
                    'elements' => $elements,
                    'contribution' => $contribution,
                    'total' => $total
                ];

                //Création de la facture
                $invoice = new Invoice();
                $invoice->setCreatedAt(new \DateTimeImmutable());
                $invoice->setReference($reference);
                $file= $reference.'.pdf';
                $invoice->setFile($file);
                $this->em->persist($invoice);
                $this->em->flush();
                
                //Génération du PDF
                $html = $this->twig->render('pdf/invoice.html.twig', $data);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $filePath = 'invoice/'.$reference.'.pdf';
                $output = $dompdf->output();
               
            } else {
                // throw $this->createNotFoundException(
                //     'Pas de commande trouvée avec la référence ' . $reference
                // );
            }
        } else {
            // $this->addFlash('alert', 'Cette commande n\'existe pas!');

            // return $this->redirectToRoute('app_home');
        }
        $filePath = '../invoice/'.$file;
        $output = $dompdf->output();

        return  file_put_contents($filePath, $output);
    }
}

