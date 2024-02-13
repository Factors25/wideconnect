<?php

namespace App\Entity\Trait\EntityLifeCycle;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait EntityLifeCycleCreateTrait
{
    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $createdBy = null;

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function isCreated(): bool
    {
        return ($this->createdAt !== null || $this->createdBy !== null);
    }
}