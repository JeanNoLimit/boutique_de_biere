<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class InvoiceController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route("/invoices/{filename}", name:"invoice_download")]
    public function downloadInvoice($filename) : Response
    {
        
        $filePath = $this->getParameter('kernel.project_dir') . '/var/storage/invoices/' . $filename;

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('File not found');
        }

        // Utilisez BinaryFileResponse pour envoyer le fichier au navigateur.
        // https://symfony.com/doc/current/components/http_foundation.html#serving-files
        $response = new BinaryFileResponse($filePath);
        $response->headers->set('Content-Type', 'application/pdf'); // Définissez le type MIME approprié pour le fichier.

        return $response;
    }
}