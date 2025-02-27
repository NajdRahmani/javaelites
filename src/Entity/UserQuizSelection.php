<?php
// src/Entity/UserQuizSelection.php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserQuizSelectionRepository;

#[ORM\Entity(repositoryClass: UserQuizSelectionRepository::class)]
class UserQuizSelection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $username;

    #[ORM\ManyToOne(targetEntity: Quiz::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $quiz;

    #[ORM\Column(type: 'datetime')]
    private $selectionDate;

    #[ORM\OneToMany(targetEntity: UserQuizResult::class, mappedBy: 'userQuizSelection', cascade: ['persist', 'remove'])]
    private Collection $userQuizResults;

    public function __construct()
    {
        $this->userQuizResults = new ArrayCollection();
        $this->selectionDate = new \DateTime(); // Initialize selectionDate to current date and time
    }

    // Getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;
        return $this;
    }

    public function getSelectionDate(): ?\DateTimeInterface
    {
        return $this->selectionDate;
    }

    public function setSelectionDate(\DateTimeInterface $selectionDate): self
    {
        $this->selectionDate = $selectionDate;
        return $this;
    }

    /**
     * @return Collection<int, UserQuizResult>
     */
    public function getUserQuizResults(): Collection
    {
        return $this->userQuizResults;
    }

    public function addUserQuizResult(UserQuizResult $userQuizResult): self
    {
        if (!$this->userQuizResults->contains($userQuizResult)) {
            $this->userQuizResults[] = $userQuizResult;
            $userQuizResult->setUserQuizSelection($this);
        }
        return $this;
    }

    public function removeUserQuizResult(UserQuizResult $userQuizResult): self
    {
        if ($this->userQuizResults->removeElement($userQuizResult)) {
            if ($userQuizResult->getUserQuizSelection() === $this) {
                $userQuizResult->setUserQuizSelection(null);
            }
        }
        return $this;
    }
}