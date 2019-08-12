<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrassirNvrRepository")
 * @UniqueEntity(
 *     fields={"Ip"},
 *     errorPath="Ip",
 *     message="Ip address already exists"
 *    )
 */
class TrassirNvr
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\Ip
     */
    private $Ip;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $guid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastHealthAndDataCollectedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIp(): ?string
    {
        return $this->Ip;
    }

    public function setIp(string $Ip): self
    {
        $this->Ip = $Ip;

        return $this;
    }

    public function getGuid(): ?string
    {
        return $this->guid;
    }

    public function setGuid(?string $guid): self
    {
        $this->guid = $guid;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastHealthAndDataCollectedAt(): ?\DateTimeInterface
    {
        return $this->lastHealthAndDataCollectedAt;
    }

    public function setLastHealthAndDataCollectedAt(?\DateTimeInterface $lastHealthAndDataCollectedAt): self
    {
        $this->lastHealthAndDataCollectedAt = $lastHealthAndDataCollectedAt;

        return $this;
    }
}
