<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrassirNvrDataRepository")
 */
class TrassirNvrData
{
    const HEALTH_POINTS=[
    'disks'=>'Disks',
    'uptime'=>'Uptime',
    'network'=>'Network',
    'cpu_load'=>'CPU Load',
    'database'=>'Database',
    'automation'=>'Automation',
    'channels_total'=>'Channels Total',
    'channels_online'=>'Channels Online',
    'disks_stat_main_days'=>'Archive Main',
    'disks_stat_subs_days'=>'Archive Sub',
        ];
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

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateTime;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $success;

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

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(?\DateTimeInterface $dateTime): self
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getSuccess(): ?bool
    {
        return $this->success;
    }

    public function setSuccess(?bool $success): self
    {
        $this->success = $success;

        return $this;
    }
}
