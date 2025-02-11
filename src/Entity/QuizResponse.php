<?php
// src/Entity/QuizResponse.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'quiz_reponse')]
class QuizResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $texte;

    #[ORM\Column(type: 'boolean')]
    private $estCorrecte;

    #[ORM\ManyToOne(targetEntity: QUIZQUESTION::class, inversedBy: 'reponses')]
    #[ORM\JoinColumn(nullable: false)]
    private $question;

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): self
    {
        $this->texte = $texte;
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