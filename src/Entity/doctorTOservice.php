<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="doctorTOservice")
 */
class DoctorToService
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="Duser_id", referencedColumnName="id")
     */
    private $DuserId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     */
    private $serviceId;

    // Геттер для поля "id"
    public function getId(): ?int
    {
        return $this->id;
    }

    // Геттер и сеттер для связи с сущностью User
    public function getDuser(): ?int
    {
        return $this->DuserId;
    }

    public function setDuser(int $Duser): void
    {
        $this->DuserId = $Duser;
    }

    // Геттер и сеттер для связи с сущностью Service
    public function getService(): ?int
    {
        return $this->serviceId;
    }

    public function setService(int $service): void
    {
        $this->serviceId = $service;
    }
    
}
