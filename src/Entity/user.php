<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDoctor;

    public function eraseCredentials(){}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
    // Возвращает уникальный идентификатор пользователя, в данном случае используется поле "id"
    return (string) $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $hashedPassword = $this->hashPassword($password);
        $this->password = $hashedPassword;
    }

    public function isDoctor(): bool
    {
        return $this->isDoctor;
    }

    public function setDoctor(bool $isDoctor): void
    {
        $this->isDoctor = $isDoctor;
    }

    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        if ($this->isDoctor) {
            return ['ROLE_DOCTOR'];
        }

        return ['ROLE_PATIENT'];
    }

    public function hashPassword(string $password): string
    {
        // Хеширование пароля
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function isPasswordValid(string $password): bool
    {
        // Проверка соответствия хешированного пароля
        return password_verify($password, $this->getPassword());
    }
}