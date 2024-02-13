<?php

namespace App\Entity;

use App\Entity\Trait\EntityLifeCycle\EntityLifeCycleTrait;
use App\Repository\UserRepository;
use App\Service\Security\SecurityConstant;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'])]
#[UniqueEntity(fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use EntityLifeCycleTrait {
        EntityLifeCycleTrait::__construct as private EntityLifeCycleTrait;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    private ?string $email;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?array $roles;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, minMessage: 'Votre mot de passe doit contenir au moins 10 caractères.')]
    #[Assert\Regex(pattern: '/[a-z]/', message: 'Votre mot de passe doit contenir au moins une lettre minuscule.')]
    #[Assert\Regex(pattern: '/[A-Z]/', message: 'Votre mot de passe doit contenir au moins une lettre majuscule.')]
    #[Assert\Regex(pattern: '/[0-9]/', message: 'Votre mot de passe doit contenir au moins un chiffre.')]
    #[Assert\Regex(pattern: '/\W/', message: 'Votre mot de passe doit contenir au moins un caractère spécial.')]
    private ?string $password;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    private ?string $username;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?bool $active;

    #[ORM\Column(type: 'json')]
    #[Assert\NotNull]
    private ?array $token;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?bool $verify;

    public function __construct()
    {
        $this->EntityLifeCycleTrait(true);

        $this->id = null;
        $this->email = null;
        $this->roles = [SecurityConstant::ROLE_USER];
        $this->password = null;
        $this->username = null;
        $this->active = false;
        $this->token = [];
        $this->verify = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalt(): void
    {

    }

    public function eraseCredentials(): void
    {

    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getToken(): array
    {
        return $this->token;
    }

    public function setToken(?array $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function isVerify(): ?bool
    {
        return $this->verify;
    }

    public function setVerify(?bool $verify): self
    {
        $this->verify = $verify;

        return $this;
    }
}