<?php

namespace App\Entity\Trait\EntityLifeCycle;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait EntityLifeCycleArchiveTrait
{
    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $archivedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $archivedBy = null;

    public function getArchivedAt(): ?DateTimeImmutable
    {
        return $this->archivedAt;
    }

    public function setArchivedAt(?DateTimeImmutable $archivedAt): self
    {
        $this->archivedAt = $archivedAt;
        return $this;
    }

    public function getArchivedBy(): ?User
    {
        return $this->archivedBy;
    }

    public function setArchivedBy(?User $archivedBy): self
    {
        $this->archivedBy = $archivedBy;
        return $this;
    }

    public function isArchived(): bool
    {
        return ($this->archivedAt !== null || $this->archivedBy !== null);
    }
}