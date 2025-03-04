<?php

namespace App\Service;

use App\Entity\Evaluation;
use App\Entity\Resultat;
use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;

class StatisticsService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function calculateStatistics(Evaluation $evaluation): array
    {
        // Calculer le score maximum
        $questions = $evaluation->getQuestions();
        $scoreMaximum = 0;
        foreach ($questions as $question) {
            $scoreMaximum += $question->getPoint();
        }

        // Compter le nombre de réponses
        $nombreReponses = $this->entityManager
            ->getRepository('App\Entity\Reponse')
            ->count(['evaluation' => $evaluation]);

        // Déterminer le statut
        $now = new \DateTime();
        $deadline = $evaluation->getDeadline();
        $status = 'En cours';
        
        if ($deadline < $now) {
            $status = 'Terminée';
        } elseif ($deadline > $now) {
            $status = 'À venir';
        }

        return [
            'scoreMaximum' => $scoreMaximum,
            'nombreReponses' => $nombreReponses,
            'status' => $status
        ];
    }
}