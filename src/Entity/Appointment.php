<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="appointments")
 */
class Appointment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="Duser_id", referencedColumnName="id")
     */
    private $Duser;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $userId;

    /**
     * @ORM\ManyToOne(targetEntity="Service")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     */
    private $serviceId;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    // Геттер для поля "id"
    public function getId(): ?int
    {
        return $this->id;
    }

    // Геттер и сеттер для поля "Duser"
    public function getDUser(): ?User
    {
        return $this->Duser;
    }

    public function setDUser(?User $Duser): void
    {
        $this->Duser = $Duser;
    }

    // Геттер и сеттер для поля "user"
    public function getUser(): ?User
    {
        return $this->userId;
    }

    public function setUser(?User $userId): void
    {
        $this->userId = $userId;
    }

    // Геттер и сеттер для поля "service"
    public function getService(): ?Service
    {
        return $this->serviceId;
    }

    public function setService(?Service $serviceId): void
    {
        $this->serviceId = $serviceId;
    }

    // Геттер и сеттер для поля "date"
    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): void
    {
        $this->date = $date;
    }
}
