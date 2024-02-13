<?php

namespace App\Service\Manager;

use App\Service\Environment\Env;
use App\Service\Validation\Error\Error;
use App\Service\Validation\Error\UnknownError;
use App\Service\Validation\Success\Success;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ManagerService
{
    public const ENTITY_GET_METHODS = ['is', 'getIs'];
    public const ENTITY_SET_METHODS = ['set', 'setIs'];

    public const METHOD_IS_ABLE_TO_CREATE = 'isAbleToCreate';
    public const METHOD_IS_ABLE_TO_UPDATE = 'isAbleToUpdate';
    public const METHOD_IS_ABLE_TO_DELETE = 'isAbleToDelete';
    public const METHOD_IS_ABLE_TO_ARCHIVE = 'isAbleToArchive';

    public const ENTITY_MESSAGE_DELETED = 'L\'élément a été supprimé et ne peut pas être modifié';
    public const ENTITY_MESSAGE_NOT_DELETED = 'L\'élément n\'est pas supprimé et ne peut pas être modifié';

    public const ENTITY_MESSAGE_ARCHIVED = 'L\'élément a été archivé et ne peut pas être modifié';
    public const ENTITY_MESSAGE_NOT_ARCHIVED = 'L\'élément n\'est pas archivé et ne peut pas être modifié';

    private ?UserInterface $user;

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ValidatorInterface     $validator,
        private readonly TokenStorageInterface  $tokenStorage,
        private readonly KernelInterface        $kernel
    )
    {
        $this->user = $this->tokenStorage?->getToken()?->getUser();
    }

    /**
     * @param $entity
     * @return array
     */
    #[ArrayShape(['success' => 'int', 'errors' => 'array'])]
    public function create($entity): array
    {
        try {
            $entityValidator = $this->entityValidator($entity, self::METHOD_IS_ABLE_TO_CREATE);
            if ($entityValidator['success'] === false) return $entityValidator;

            $errors = $this->validator->validate($entity);
            if (count($errors) > 0) return Error::generate($errors);

            $entity->setCreatedAt(new DateTimeImmutable());
            $entity->setCreatedBy($this->user);

            $entity->setUpdatedAt(new DateTimeImmutable());
            $entity->setUpdatedBy($this->user);

            $this->em->persist($entity);
            $this->em->flush();
        } catch (Exception $exception) {
            return Error::generate(Env::isDev() ? $exception->getMessage() : UnknownError::DEFAULT_MESSAGE);
        }

        return Success::generate();
    }

    /**
     * @param $entity
     * @return array
     */
    #[ArrayShape(['success' => 'int', 'errors' => 'array'])]
    public function update($entity): array
    {
        try {
            $entityValidator = $this->entityValidator($entity, self::METHOD_IS_ABLE_TO_UPDATE);
            if ($entityValidator['success'] === false) return $entityValidator;

            $errors = $this->validator->validate($entity);
            if (count($errors) > 0) return Error::generate($errors);

            $entity->setUpdatedAt(new DateTimeImmutable());
            $entity->setUpdatedBy($this->user);

            $this->em->persist($entity);
            $this->em->flush();
        } catch (Exception $exception) {
            return Error::generate(Env::isDev() ? $exception->getMessage() : UnknownError::DEFAULT_MESSAGE);
        }

        return Success::generate();
    }

    /**
     * @param $entity
     * @return array
     */
    #[ArrayShape(['success' => 'int', 'errors' => 'array'])]
    public function delete($entity): array
    {
        try {
            $entityValidator = $this->entityValidator($entity, self::METHOD_IS_ABLE_TO_DELETE);
            if ($entityValidator['success'] === false) return $entityValidator;

            if ($entity->getSoftDelete()) {
                $entity->setUpdatedAt(new DateTimeImmutable());
                $entity->setUpdatedBy($this->user);

                $entity->setDeletedAt(new DateTimeImmutable());
                $entity->setDeletedBy($this->user);

                $this->em->persist($entity);
                $this->em->flush();

                return Success::generate();
            }

            $this->em->remove($entity);
            $this->em->flush();
        } catch (Exception $exception) {
            return Error::generate(Env::isDev() ? $exception->getMessage() : UnknownError::DEFAULT_MESSAGE);
        }

        return Success::generate();
    }

    /**
     * @param $entity
     * @return array
     */
    #[ArrayShape(['success' => 'int', 'errors' => 'array'])]
    public function archive($entity): array
    {
        try {
            $entityValidator = $this->entityValidator($entity, self::METHOD_IS_ABLE_TO_ARCHIVE);
            if ($entityValidator['success'] === false) return $entityValidator;

            $errors = $this->validator->validate($entity);
            if (count($errors) > 0) return Error::generate($errors);

            $entity->setUpdatedAt(new DateTimeImmutable());
            $entity->setUpdatedBy($this->user);

            $entity->setArchivedAt(new DateTimeImmutable());
            $entity->setArchivedBy($this->user);

            $this->em->persist($entity);
            $this->em->flush();
        } catch (Exception $exception) {
            return Error::generate(Env::isDev() ? $exception->getMessage() : UnknownError::DEFAULT_MESSAGE);
        }

        return Success::generate();
    }

    /**
     * @param $entity
     * @param $column
     * @return array
     */
    #[ArrayShape(['success' => 'int', 'errors' => 'array'])]
    public function toggle($entity, $column): array
    {
        try {
            if (!property_exists($entity, $column)) {
                return Error::generate(Env::isDev() ? "Property $column doesn't exist" : UnknownError::DEFAULT_MESSAGE);
            }

            $method = 'isAbleTo' . ucfirst($column);

            $entityValidator = $this->entityValidator($entity, $method);
            if ($entityValidator['success'] === false) return $entityValidator;

            $getterFound = false;

            foreach (self::ENTITY_GET_METHODS as $ENTITY_GET_METHOD) {
                $getter = $ENTITY_GET_METHOD . ucfirst($column);

                if (method_exists($entity, $getter)) {
                    $getterFound = $getter;
                    break;
                }
            }

            if ($getterFound === false) return Error::generate(Env::isDev() ? "Getter for column '$column' doesn't exist" : UnknownError::DEFAULT_MESSAGE);

            foreach (self::ENTITY_SET_METHODS as $ENTITY_SET_METHOD) {
                $setter = $ENTITY_SET_METHOD . ucfirst($column);

                if (method_exists($entity, $setter)) {
                    $entity->$setter(!$entity->$getterFound());
                    return $this->update($entity);
                }
            }
        } catch (Exception $e) {
            return Error::generate(Env::isDev() ? $e->getMessage() : UnknownError::DEFAULT_MESSAGE);
        }

        return Success::generate();
    }

    /**
     * @param $entity
     * @return array
     */
    #[ArrayShape(['success' => 'int', 'errors' => 'array'])]
    public function unArchive($entity): array
    {
        if (!$entity->isArchived()) return Error::generate(self::ENTITY_MESSAGE_NOT_ARCHIVED);
        if ($entity->isDeleted()) return Error::generate(self::ENTITY_MESSAGE_DELETED);

        try {
            $entity->setUpdatedAt(new DateTimeImmutable());
            $entity->setUpdatedBy($this->user);

            $entity->setArchivedAt(null);
            $entity->setArchivedBy(null);

            return $this->update($entity);
        } catch (Exception $exception) {
            return Error::generate(Env::isDev() ? $exception->getMessage() : UnknownError::DEFAULT_MESSAGE);
        }
    }

    /**
     * @param $entity
     * @return array
     */
    #[ArrayShape(['success' => 'int', 'errors' => 'array'])]
    public function unDelete($entity): array
    {
        if (!$entity->isDeleted()) return Error::generate(self::ENTITY_MESSAGE_NOT_DELETED);
        if ($entity->isArchived()) return Error::generate(self::ENTITY_MESSAGE_ARCHIVED);

        try {
            $entity->setUpdatedAt(new DateTimeImmutable());
            $entity->setUpdatedBy($this->user);

            $entity->setDeletedAt(null);
            $entity->setDeletedBy(null);

            return $this->update($entity);
        } catch (Exception $e) {
            return Error::generate(Env::isDev() ? $e->getMessage() : UnknownError::DEFAULT_MESSAGE);
        }
    }

    /**
     * @throws ReflectionException
     */
    private function entityValidator($entity, $method): array
    {
        $container = $this->kernel->getContainer();

        if ($entity->isDeleted()) return Error::generate(self::ENTITY_MESSAGE_DELETED);
        if ($entity->isArchived()) return Error::generate(self::ENTITY_MESSAGE_ARCHIVED);

        $reflection = new ReflectionClass($entity);

        while ($reflection->getParentClass()) {
            $shortName = strtolower(preg_replace('/\B([A-Z])/', '_$1', $reflection->getShortName()));
            $entityValidator = $container->has('app.validator.' . $shortName) ? $container->get('app.validator.' . $shortName) : null;

            if (
                $entityValidator &&
                method_exists($entityValidator, $method)
            ) {
                return $entityValidator->$method($entity);
            }

            $reflection = $reflection->getParentClass();
        }

        $shortName = strtolower(preg_replace('/\B([A-Z])/', '_$1', $reflection->getShortName()));
        $entityValidator = $container->has('app.validator.' . $shortName) ? $container->get('app.validator.' . $shortName) : null;

        if (
            $entityValidator &&
            method_exists($entityValidator, $method)
        ) {
            return $entityValidator->$method($entity);
        }

        return Success::generate();
    }
}