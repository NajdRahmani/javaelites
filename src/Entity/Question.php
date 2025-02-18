<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ORM\HasLifecycleCallbacks]  // Ajouter cette annotation pour activer les événements du cycle de vie
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le texte de la question ne peut pas être vide.")]
    private ?string $text = null;

    #[ORM\Column]
    #[Assert\Positive(message: "Le nombre de points doit être positif.")]
    private ?int $point = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero(message: "L'ordre de la question doit être positif ou égal à zéro.")]
    private ?int $ordreQuestion = 0;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Evaluation $evaluation = null;

    #[ORM\OneToMany(targetEntity: Reponse::class, mappedBy: 'question', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $reponses;

    #[ORM\Column(length: 255, nullable: true)]  // Marquer comme nullable
    private ?string $title = null;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    // Getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;
        return $this;
    }

    public function getPoint(): ?int
    {
        return $this->point;
    }

    public function setPoint(int $point): static
    {
        $this->point = $point;
        return $this;
    }

    public function getOrdreQuestion(): ?int
    {
        return $this->ordreQuestion;
    }

    public function setOrdreQuestion(int $ordreQuestion): static
    {
        $this->ordreQuestion = $ordreQuestion;
        return $this;
    }

    public function getEvaluation(): ?Evaluation
    {
        return $this->evaluation;
    }

    public function setEvaluation(?Evaluation $evaluation): static
    {
        $this->evaluation = $evaluation;
        return $this;
    }

    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): static
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses->add($reponse);
            $reponse->setQuestion($this);
        }
        return $this;
    }

    public function removeReponse(Reponse $reponse): static
    {
        if ($this->reponses->removeElement($reponse)) {
            if ($reponse->getQuestion() === $this) {
                $reponse->setQuestion(null);
            }
        }
        return $this;
    }

    // Ajout de la méthode getTitle et setTitle
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    // Méthode pour définir l'ordre des questions
    #[ORM\PrePersist]  // Appelé avant la persistance de l'entité
    public function setDefaultOrdreQuestion(): void
    {
        if ($this->getOrdreQuestion() === null) {
            // Récupérer l'évaluation associée à la question
            $evaluation = $this->getEvaluation();

            // Trouver la dernière question existante dans la même évaluation
            $lastQuestion = $evaluation->getQuestions()->last();

            // Si une question existe, l'ordre est incrémenté de 1
            if ($lastQuestion) {
                $this->setOrdreQuestion($lastQuestion->getOrdreQuestion() + 1);
            } else {
                // Sinon, commencer à 1
                $this->setOrdreQuestion(1);
            }
        }
    }
}
