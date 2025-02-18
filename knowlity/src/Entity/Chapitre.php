<?php

namespace App\Entity;

use App\Repository\ChapitreRepository;
use App\Validator\Constraints\UniqueChapterOrder;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ChapitreRepository::class)]
#[ORM\UniqueConstraint(name: 'unique_chapter_order_per_course', columns: ['chapOrder', 'cours_id'])]
#[UniqueChapterOrder] // Contrainte personnalisée pour l'unicité de l'ordre dans un cours
class Chapitre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre ne peut pas être vide.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le titre ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $title = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "L'ordre du chapitre ne peut pas être vide.")]
    #[Assert\PositiveOrZero(message: "L'ordre du chapitre doit être un nombre positif ou zéro.")]
    private ?int $chapOrder = null;

    #[ORM\ManyToOne(inversedBy: 'chapitres')]
    #[Assert\NotNull(message: "Le cours associé ne peut pas être vide.")]
    private ?Cours $cours = null;

    #[ORM\Column(length: 255)]
    
    private ?string $contenu = null;

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
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

    public function getChapOrder(): ?int
    {
        return $this->chapOrder;
    }

    public function setChapOrder(int $chapOrder): static
    {
        $this->chapOrder = $chapOrder;

        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): static
    {
        $this->cours = $cours;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }
}