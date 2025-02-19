<?php

namespace App\Entity;

use App\Repository\EvaluationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EvaluationRepository::class)]
class Evaluation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\GreaterThan(value: 1, message: "Le score maximum doit être supérieur à 1.")]
    private ?int $max_score = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $create_at = null;
    

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\GreaterThan("today", message: "La date limite doit être supérieure à la date actuelle.")]
    private ?\DateTimeInterface $deadline = null;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'evaluation', orphanRemoval: true)]
    private Collection $Questions;

    #[ORM\Column(type: 'integer')]
    #[Assert\PositiveOrZero(message: "Le nombre de cours ne peut pas être négatif.")]
    private ?int $cours = null;

    public function __construct()
    {
        $this->Questions = new ArrayCollection();
        $this->create_at = new \DateTime(); // Définit la date actuelle automatiquement
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getMaxScore(): ?int
    {
        return $this->max_score;
    }

    public function setMaxScore(int $max_score): static
    {
        $this->max_score = $max_score;
        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeInterface $create_at): static
    {
        $this->create_at = $create_at;
        return $this;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(\DateTimeInterface $deadline): static
    {
        $this->deadline = $deadline;
        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->Questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->Questions->contains($question)) {
            $this->Questions->add($question);
            $question->setEvaluation($this);
        }
        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->Questions->removeElement($question)) {
            if ($question->getEvaluation() === $this) {
                $question->setEvaluation(null);
            }
        }
        return $this;
    }

    public function getCours(): ?int
    {
        return $this->cours;
    }

    public function setCours(int $cours): static
    {
        $this->cours = $cours;
        return $this;
    }
}
