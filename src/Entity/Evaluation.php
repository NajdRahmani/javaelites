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
    #[Assert\NotBlank(message: "⚠️ Le titre est obligatoire")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "⚠️ Le titre doit contenir au moins {{ limit }} caractères",
        maxMessage: "⚠️ Le titre ne peut pas dépasser {{ limit }} caractères"
    )]
    private ?string $title = '';

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "⚠️ La description est obligatoire")]
    #[Assert\Length(
        min: 10,
        minMessage: "⚠️ La description doit contenir au moins {{ limit }} caractères"
    )]
    private ?string $description = '';

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le score maximum est obligatoire")]
    #[Assert\Positive(message: "Le score maximum doit être un nombre positif. Exemple: 20")]
    #[Assert\Range(
        min: 1,
        max: 100,
        notInRangeMessage: "Le score doit être entre {{ min }} et {{ max }}"
    )]
    private ?int $max_score = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $create_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "La date limite est obligatoire")]
    #[Assert\GreaterThan("today", message: "La date limite doit être une date future. Format: jj/mm/aaaa")]
    private ?\DateTimeInterface $deadline = null;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'evaluation', orphanRemoval: true)]
    private Collection $Questions;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\NotBlank(message: "Le nombre de cours est obligatoire")]
    #[Assert\Positive(message: "Le nombre de cours doit être positif. Exemple: 1, 2, 3...", groups: ['create', 'edit'])]
    #[Assert\Range(
        min: 1,
        max: 100,
        notInRangeMessage: "Le nombre de cours doit être compris entre {{ min }} et {{ max }}",
        groups: ['create', 'edit']
    )]
    private ?int $cours = null;

    public function __construct()
    {
        $this->Questions = new ArrayCollection();
        $this->create_at = new \DateTime();
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

    public function setCours(?int $cours): static
    {
        $this->cours = $cours;
        return $this;
    }
}
