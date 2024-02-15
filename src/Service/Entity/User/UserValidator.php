<?php

namespace App\Service\Entity\User;

use App\Entity\User;
use App\Service\Entity\ValidatorInterface;
use InvalidArgumentException;

class UserValidator implements ValidatorInterface
{
    /**
     * @param User $entity
     * @return array
     */
    public function isAbleToCreate($entity): array
    {
        $this->validateEntityType($entity);
        return ['success' => true, 'errors' => []];
    }

    /**
     * @param User $entity
     * @return array
     */
    public function isAbleToUpdate($entity): array
    {
        $this->validateEntityType($entity);
        return ['success' => true, 'errors' => []];
    }

    /**
     * @param User $entity
     * @return array
     */
    public function isAbleToDelete($entity): array
    {
        $this->validateEntityType($entity);
        return ['success' => true, 'errors' => []];
    }

    /**
     * @param User $entity
     * @return array
     */
    public function isAbleToArchive($entity): array
    {
        $this->validateEntityType($entity);
        return ['success' => true, 'errors' => []];
    }

    public function validateEntityType($entity): void
    {
        if (!$entity instanceof User) {
            throw new InvalidArgumentException("Invalid entity type. Expected User");
        }
    }
}