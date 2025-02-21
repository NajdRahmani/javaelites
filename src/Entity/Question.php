<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ORM\HasLifecycleCallbacks]  
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "⚠️ L'énoncé de la question est obligatoire")]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: "⚠️ L'énoncé doit contenir au moins {{ limit }} caractères",
        maxMessage: "⚠️ L'énoncé ne peut pas dépasser {{ limit }} caractères"
    )]
    private ?string $text = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "⚠️ Le nombre de points est obligatoire")]
    #[Assert\Range(
        min: 0.5,
        max: 20,
        notInRangeMessage: "⚠️ Le nombre de points doit être compris entre {{ min }} et {{ max }}"
    )]
    private ?int $point = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "⚠️ L'ordre de la question est obligatoire")]
    #[Assert\Range(
        min: 1,
        max: 100,
        notInRangeMessage: "⚠️ L'ordre doit être compris entre {{ min }} et {{ max }}"
    )]
    private ?int $ordreQuestion = 0;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Evaluation $evaluation = null;

    #[ORM\OneToMany(targetEntity: Reponse::class, mappedBy: 'question', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $reponses;

    #[ORM\Column(length: 255, nullable: true)]  
    private ?string $title = null;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    #[ORM\PrePersist]  
    public function setDefaultOrdreQuestion(): void
    {
        if ($this->getOrdreQuestion() === null) {
            $evaluation = $this->getEvaluation();

            $lastQuestion = $evaluation->getQuestions()->last();

            if ($lastQuestion) {
                $this->setOrdreQuestion($lastQuestion->getOrdreQuestion() + 1);
            } else {
                $this->setOrdreQuestion(1);
            }
        }
    }
}
