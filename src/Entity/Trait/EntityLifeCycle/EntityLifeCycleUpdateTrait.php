<?php

namespace App\Entity\Trait\EntityLifeCycle;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait EntityLifeCycleUpdateTrait
{
    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $updatedBy = null;

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?User $updatedBy): self
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }

    public function isUpdated(): bool
    {
        return ($this->updatedAt !== null || $this->updatedBy !== null);
    }
}