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
    private $check1 = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $check2 = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $check3 = false;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="checklist", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCheck1(): ?bool
    {
        return $this->check1;
    }

    public function setCheck1(bool $check1): self
    {
        $this->check1 = $check1;

        return $this;
    }

    public function getCheck2(): ?bool
    {
        return $this->check2;
    }

    public function setCheck2(bool $check2): self
    {
        $this->check2 = $check2;

        return $this;
    }

    public function getCheck3(): ?bool
    {
        return $this->check3;
    }

    public function setCheck3(bool $check3): self
    {
        $this->check3 = $check3;

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
