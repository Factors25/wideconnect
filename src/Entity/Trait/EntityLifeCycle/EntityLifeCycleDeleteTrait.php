<?php

namespace App\Entity\Trait\EntityLifeCycle;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait EntityLifeCycleDeleteTrait
{
    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $deletedBy = null;

    #[ORM\Column(nullable: false)]
    private bool $softDelete = false;

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    public function getDeletedBy(): ?User
    {
        return $this->deletedBy;
    }

    public function setDeletedBy(?User $deletedBy): self
    {
        $this->deletedBy = $deletedBy;
        return $this;
    }

    public function getSoftDelete(): bool
    {
        return $this->softDelete;
    }

    public function setSoftDelete(?bool $softDelete): self
    {
        $this->softDelete = $softDelete;
        return $this;
    }

    public function isDeleted(): bool
    {
        return ($this->deletedAt !== null || $this->deletedBy !== null);
    }
}