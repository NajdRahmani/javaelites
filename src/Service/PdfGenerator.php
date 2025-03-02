<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Environment;

class PdfGenerator
{
    private $twig;
    private $parameterBag;

    public function __construct(Environment $twig, ParameterBagInterface $parameterBag)
    {
        $this->twig = $twig;
        $this->parameterBag = $parameterBag;
    }

    public function generateRegistrationPdf($user, $cours): string
    {
        // Configuration des options de Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');
    
        $html = $this->twig->render('pdf/registration_confirmation.html.twig', [
            'user' => $user,
            'cours' => $cours,
            'app_name' => $this->parameterBag->get('app_name'),
        ]);
    
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        return $dompdf->output();
    }
}