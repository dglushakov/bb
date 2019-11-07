<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlarmSystemRepository")
 * @UniqueEntity(
 * fields={"facility"},
 * message="Facility already connected to another system."
 * )
 */
class AlarmSystem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Facility")
     */
    private $facility;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pkp;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $keyboard;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $motion_sensor;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $fire_sensor;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $door_sensor;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stationary_button;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wearable_button;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $securityProvider;

    public static function getSecurityProvidersList() {
        $secProvideers=[
            'Гольфстрим'=>'Гольфстрим',
            'Дельта'=>'Дельта',
            'Локальный подрядчик'=>'Локальный подрядчик',
        ];

        return $secProvideers;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getFacility(): ?Facility
    {
        return $this->facility;
    }

    public function setFacility(?Facility $facility): self
    {
        $this->facility = $facility;

        return $this;
    }

    public function getPkp(): ?int
    {
        return $this->pkp;
    }

    public function setPkp(?int $pkp): self
    {
        $this->pkp = $pkp;

        return $this;
    }

    public function getKeyboard(): ?int
    {
        return $this->keyboard;
    }

    public function setKeyboard(?int $keyboard): self
    {
        $this->keyboard = $keyboard;

        return $this;
    }

    public function getMotionSensor(): ?int
    {
        return $this->motion_sensor;
    }

    public function setMotionSensor(?int $motion_sensor): self
    {
        $this->motion_sensor = $motion_sensor;

        return $this;
    }

    public function getFireSensor(): ?int
    {
        return $this->fire_sensor;
    }

    public function setFireSensor(?int $fire_sensor): self
    {
        $this->fire_sensor = $fire_sensor;

        return $this;
    }

    public function getDoorSensor(): ?int
    {
        return $this->door_sensor;
    }

    public function setDoorSensor(?int $door_sensor): self
    {
        $this->door_sensor = $door_sensor;

        return $this;
    }

    public function getStationaryButton(): ?int
    {
        return $this->stationary_button;
    }

    public function setStationaryButton(?int $stationary_button): self
    {
        $this->stationary_button = $stationary_button;

        return $this;
    }

    public function getWearableButton(): ?int
    {
        return $this->wearable_button;
    }

    public function setWearableButton(?int $wearable_button): self
    {
        $this->wearable_button = $wearable_button;

        return $this;
    }

    public function getSecurityProvider(): ?string
    {
        return $this->securityProvider;
    }

    public function setSecurityProvider(?string $securityProvider): self
    {
        $this->securityProvider = $securityProvider;

        return $this;
    }
}
