<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="price")
 */
class Price
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Service")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     */
    private $serviceId;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    // Геттер для поля "id"
    public function getId(): ?int
    {
        return $this->id;
    }

    // Геттер и сеттер для связи с полем "Duser_id"
    public function getDuser(): ?int
    {
        return $this->DuserId;
    }

    public function setDuser(?int $DuserId): void
    {
        $this->DuserId = $DuserId;
    }

    // Геттер и сеттер для связи с полем "service_id"
    public function getService(): ?int
    {
        return $this->serviceId;
    }

    public function setService(?int $serviceId): void
    {
        $this->serviceId = $serviceId;
    }

    // Геттер и сеттер для поля "price"
    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}
