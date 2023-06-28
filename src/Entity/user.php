<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
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
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $isDoctor;

    public function eraseCredentials(){}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
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
        $this->password = $password;
    }
    

    public function isDoctor(): bool
    {
        return $this->isDoctor ?? false;
    }

    public function setIsDoctor(?bool $isDoctor): void
    {
        $this->isDoctor = $isDoctor ?? false;
    }


    public function getRoles(): array
    {
        if ($this->isDoctor) {
            return ['ROLE_DOCTOR'];
        }

        return ['ROLE_USER'];
    }

    public function isPasswordValid(string $password): bool
{
    return password_verify($password, $this->getPassword());
}
}