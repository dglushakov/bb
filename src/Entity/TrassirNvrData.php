<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrassirNvrDataRepository")
 */
class TrassirNvrData
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $health = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $objects = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TrassirNvr")
     */
    private $trassirNvrId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHealth(): ?array
    {
        return $this->health;
    }

    public function setHealth(?array $health): self
    {
        $this->health = $health;

        return $this;
    }

    public function getObjects(): ?array
    {
        return $this->objects;
    }

    public function setObjects(?array $objects): self
    {
        $this->objects = $objects;

        return $this;
    }

    public function getTrassirNvrId(): ?TrassirNvr
    {
        return $this->trassirNvrId;
    }

    public function setTrassirNvrId(?TrassirNvr $trassirNvrId): self
    {
        $this->trassirNvrId = $trassirNvrId;

        return $this;
    }
}
