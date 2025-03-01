<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Evaluation;
use App\Entity\User;

class MailService
{
    private $mailer;
    private $sender;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->sender = 'projetknowlity@gmail.com';
    }

    public function sendEvaluationSubmittedToStudent(User $student, Evaluation $evaluation)
    {
        $email = (new Email())
            ->from($this->sender)
            ->to($student->getEmail())
            ->subject('Évaluation soumise avec succès')
            ->html(
                "<h1>Votre évaluation a été enregistrée</h1>" .
                "<p>Bonjour {$student->getPrenom()} {$student->getNom()},</p>" .
                "<p>Nous confirmons que votre évaluation <strong>{$evaluation->getTitle()}</strong> a été soumise avec succès.</p>" .
                "<p>Vous serez notifié par email lorsque votre enseignant aura terminé la correction.</p>" .
                "<p>Cordialement,<br>L'équipe Knowlity</p>"
            );

        $this->mailer->send($email);
    }

    public function sendEvaluationGradedToStudent(User $student, Evaluation $evaluation)
    {
        $email = (new Email())
            ->from($this->sender)
            ->to($student->getEmail())
            ->subject('Votre évaluation a été corrigée')
            ->html(
                "<h1>Évaluation corrigée</h1>" .
                "<p>Bonjour {$student->getPrenom()} {$student->getNom()},</p>" .
                "<p>Votre évaluation <strong>{$evaluation->getTitle()}</strong> a été corrigée par votre enseignant.</p>" .
                "<p>Vous pouvez maintenant consulter vos résultats sur la plateforme.</p>" .
                "<p>Cordialement,<br>L'équipe Knowlity</p>"
            );

        $this->mailer->send($email);
    }

    public function sendEvaluationCreatedToTeacher(User $teacher, Evaluation $evaluation)
    {
        $email = (new Email())
            ->from($this->sender)
            ->to($teacher->getEmail())
            ->subject('Confirmation de création d\'évaluation')
            ->html(
                "<h1>Évaluation créée avec succès</h1>" .
                "<p>Bonjour {$teacher->getPrenom()} {$teacher->getNom()},</p>" .
                "<p>Nous confirmons que votre évaluation <strong>{$evaluation->getTitle()}</strong> a été créée avec succès.</p>" .
                "<p>Les étudiants peuvent maintenant y accéder selon les paramètres que vous avez définis.</p>" .
                "<p>Cordialement,<br>L'équipe Knowlity</p>"
            );

        $this->mailer->send($email);
    }
}
