<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user_quiz_selection')]
class UserQuizSelection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: QuizResponse::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $responseSelectionnee;

    #[ORM\Column(type: 'boolean')]
    private $estCorrecte;

    #[ORM\ManyToOne(targetEntity: QuizQuestion::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $question;

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReponseSelectionnee(): ?QuizReponse
    {
        return $this->reponseSelectionnee;
    }

    public function setReponseSelectionnee(?QuizReponse $reponseSelectionnee): self
    {
        $this->reponseSelectionnee = $reponseSelectionnee;

        return $this;
    }

    public function getEstCorrecte(): ?bool
    {
        return $this->estCorrecte;
    }

    public function setEstCorrecte(bool $estCorrecte): self
    {
        $this->estCorrecte = $estCorrecte;

        return $this;
    }

    public function getQuestion(): ?QuizQuestion
    {
        return $this->question;
    }

    public function setQuestion(?QuizQuestion $question): self
    {
        $this->question = $question;

        return $this;
    }
}