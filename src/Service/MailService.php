<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Evaluation;
use App\Entity\User;
use Symfony\Component\Mime\Address;

class MailService
{
    private $mailer;
    private $fromEmail = 'notifications@knowlity.com';
    private $fromName = 'Knowlity';

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEvaluationSubmissionNotification(User $student, Evaluation $evaluation)
    {
        $email = (new Email())
            ->from(new Address($this->fromEmail, $this->fromName))
            ->to($student->getEmail())
            ->subject('Confirmation de soumission - ' . $evaluation->getTitle())
            ->html($this->getSubmissionEmailTemplate(
                $student->getFirstname(),
                $evaluation->getTitle()
            ));

        $this->mailer->send($email);
    }

    public function sendEvaluationGradedNotification(User $student, Evaluation $evaluation)
    {
        $email = (new Email())
            ->from(new Address($this->fromEmail, $this->fromName))
            ->to($student->getEmail())
            ->subject('Évaluation corrigée - ' . $evaluation->getTitle())
            ->html($this->getGradedEmailTemplate(
                $student->getFirstname(),
                $evaluation->getTitle()
            ));

        $this->mailer->send($email);
    }

    private function getSubmissionEmailTemplate(string $firstName, string $evaluationTitle): string
    {
        return <<<HTML
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
            <h2 style="color: #2C3E50;">Confirmation de soumission</h2>
            <p>Bonjour {$firstName},</p>
            <p>Votre évaluation <strong>{$evaluationTitle}</strong> a été soumise avec succès.</p>
            <p>Vous recevrez une notification lorsque votre enseignant l'aura corrigée.</p>
            <div style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
                <p style="margin: 0; color: #6c757d;">Cordialement,<br>L'équipe Knowlity</p>
            </div>
        </div>
        HTML;
    }

    private function getGradedEmailTemplate(string $firstName, string $evaluationTitle): string
    {
        return <<<HTML
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
            <h2 style="color: #2C3E50;">Évaluation corrigée</h2>
            <p>Bonjour {$firstName},</p>
            <p>Votre évaluation <strong>{$evaluationTitle}</strong> a été corrigée.</p>
            <p>Connectez-vous à la plateforme pour consulter vos résultats.</p>
            <div style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
                <p style="margin: 0; color: #6c757d;">Cordialement,<br>L'équipe Knowlity</p>
            </div>
        </div>
        HTML;
    }
}
