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
     * @ORM\ManyToOne(targetEntity="App\Entity\Service", fetch="LAZY")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     */
    private $serviceId;

    // Геттер для поля "id"
    public function getId(): ?int
    {
        return $this->id;
    }

    // Геттер и сеттер для связи с сущностью User
    public function getDoctor(): ?User
    {
        return $this->DuserId;
    }
    
    public function setDoctor(?User $doctor): void
    {
        $this->DuserId = $doctor;
    }

    // Геттер и сеттер для связи с сущностью Service
    public function getService(): ?Service
    {
        return $this->serviceId;
    }

    public function setService(?Service $service): void
    {
        $this->serviceId = $service;
    }

    public function getServiceId(): ?int
    {
        return $this->serviceId ? $this->serviceId->getId() : null;
    }
}
