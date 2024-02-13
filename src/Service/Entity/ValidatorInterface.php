<?php

namespace App\Service\Entity;

interface ValidatorInterface
{
    public function isAbleToCreate($entity): array;

    public function isAbleToUpdate($entity): array;

    public function isAbleToDelete($entity): array;

    public function isAbleToArchive($entity): array;

    public function validateEntityType($entity): void;
}