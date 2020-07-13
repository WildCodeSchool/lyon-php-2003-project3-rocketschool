<?php

namespace App\Entity;

use App\Repository\ChecklistRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChecklistRepository::class)
 */
class Checklist
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $checkVideo = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $checkQuizz = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $checkGuide = false;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="checklist", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCheckVideo(): ?bool
    {
        return $this->checkVideo;
    }

    public function setCheckVideo(bool $checkVideo): self
    {
        $this->checkVideo = $checkVideo;

        return $this;
    }

    public function getCheckQuizz(): ?bool
    {
        return $this->checkQuizz;
    }

    public function setCheckQuizz(bool $checkQuizz): self
    {
        $this->checkQuizz = $checkQuizz;

        return $this;
    }

    public function getCheckGuide(): ?bool
    {
        return $this->checkGuide;
    }

    public function setCheckGuide(bool $checkGuide): self
    {
        $this->checkGuide = $checkGuide;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
