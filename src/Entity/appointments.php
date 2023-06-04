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
    private $DuserId;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $userID;

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

    // Геттер и сеттер для поля "dUser"
    public function getDUser(): ?int
    {
        return $this->DuserId;
    }

    public function setDUser(?int $DuserId): void
    {
        $this->DuserId = $DuserId;
    }

    // Геттер и сеттер для поля "user"
    public function getUser(): ?int
    {
        return $this->userID;
    }

    public function setUser(?int $userID): void
    {
        $this->userID = $userID;
    }

    // Геттер и сеттер для поля "service"
    public function getService(): ?int
    {
        return $this->serviceId;
    }

    public function setService(?int $serviceId): void
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
