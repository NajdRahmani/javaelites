<?php

namespace App\Service;

use App\Entity\Evaluation;
use App\Entity\User;
use App\Repository\ReponseRepository;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class PdfService
{
    private $pdf;
    private $twig;
    private $reponseRepository;

    public function __construct(
        Pdf $pdf,
        Environment $twig,
        ReponseRepository $reponseRepository
    ) {
        $this->pdf = $pdf;
        $this->twig = $twig;
        $this->reponseRepository = $reponseRepository;
    }

    public function generateEvaluationPdf(Evaluation $evaluation, User $student): Response
    {
        // Récupérer les réponses de l'étudiant
        $responses = $this->reponseRepository->findBy([
            'evaluation' => $evaluation,
            'user' => $student
        ]);

        // Calculer le score total
        $totalScore = 0;
        foreach ($responses as $response) {
            if ($response->getNote() !== null) {
                $totalScore += $response->getNote();
            }
        }

        // Générer le HTML
        $html = $this->twig->render('evaluation/pdf_template.html.twig', [
            'evaluation' => $evaluation,
            'student' => $student,
            'responses' => $responses,
            'totalScore' => $totalScore,
            'date' => $evaluation->getCreateAt() // Ajout de la date de création
        ]);

        // Générer le PDF
        $pdf = $this->pdf->getOutputFromHtml($html);

        // Retourner la réponse
        return new Response(
            $pdf,
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="evaluation.pdf"'
            ]
        );
    }
}
