<?php

namespace App\Controller;

use App\Service\MailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Evaluation;
use Doctrine\ORM\EntityManagerInterface;

class TestMailController extends AbstractController
{
    private $mailService;
    private $entityManager;

    public function __construct(MailService $mailService, EntityManagerInterface $entityManager)
    {
        $this->mailService = $mailService;
        $this->entityManager = $entityManager;
    }

    #[Route('/test/mail', name: 'app_test_mail')]
    public function testMail(): Response
    {
        try {
            // Récupérer un utilisateur et une évaluation de test
            $user = $this->entityManager->getRepository(User::class)->findOneBy([]);
            $evaluation = $this->entityManager->getRepository(Evaluation::class)->findOneBy([]);

            if (!$user || !$evaluation) {
                return new Response('Impossible de tester : aucun utilisateur ou évaluation trouvé dans la base de données.');
            }

            // Test d'envoi des trois types d'emails
            $this->mailService->sendEvaluationSubmittedToStudent($user, $evaluation);
            $this->mailService->sendEvaluationGradedToStudent($user, $evaluation);
            $this->mailService->sendEvaluationCreatedToTeacher($user, $evaluation);

            return new Response('Emails envoyés avec succès ! Vérifiez votre boîte de réception.');

        } catch (\Exception $e) {
            return new Response('Erreur lors de l\'envoi des emails : ' . $e->getMessage());
        }
    }
}
