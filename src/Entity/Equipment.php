<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EquipmentRepository")
 * @UniqueEntity(
 * fields={"type", "make", "model"},
 * message="Already exists."
 * )
 */
class Equipment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $make;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $model;


    public static function getEquipmentTypes()
    {
        $types = [
            'Safe' => 'Safe',
            'Camera' => 'Camera',
            'HDD'=>'HDD',
            'Switch' => 'Switch',
            'NVR' => 'NVR',
            'UPS' => 'UPS',
        ];

        return $types;
    }

    public static function getEquipmentMakes()
    {
        $makes = [
            'Cobalt' => 'Cobalt',
            'Safetronics '=>'Safetronics ',
            'Valberg' => 'Valberg',
            'Контур' => 'Контур',
            'Меткон' => 'Меткон',
            'Western Digital' => 'Western Digital',
            'Zyxel' => 'Zyxel',
            'D-Link' => 'D-Link',
            'DSSL' => 'DSSL',
            'HikVision' => 'HikVision',
            'Ippon' => 'Ippon',
        ];

        return $makes;
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getMake(): ?string
    {
        return $this->make;
    }

    public function setMake(?string $make): self
    {
        $this->make = $make;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

}
