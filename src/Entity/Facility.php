<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FacilityRepository")
 */
class Facility
{
    const COUNTRIES = [
        'RU' => 'Russia',
        'RB' => 'Belarus',
        'UK' => 'Ukraine',
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lmcode;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $postCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $streetType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $street;

    /**
     * @return mixed
     */
    public function getAddress()
    {
        $this->country=='Russia'?$country='Россия ':$country='';
        if (isset($this->streetType)) {
            $strType =self::getStreetTypes()["$this->streetType"];
        } else {
            $strType='';
        }

        return $country.$this->city .' '. $strType .' '. $this->street .' '. $this->house;
    }

    /**
     * @return mixed
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * @param mixed $postCode
     */
    public function setPostCode($postCode): void
    {
        $this->postCode = $postCode;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region): void
    {
        $this->region = $region;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street): void
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getStreetType()
    {
        return $this->streetType;
    }

    /**
     * @param mixed $streetType
     */
    public function setStreetType($streetType): void
    {
        $this->streetType = $streetType;
    }

    /**
     * @return mixed
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * @param mixed $house
     */
    public function setHouse($house): void
    {
        $this->house = $house;
    }

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $house;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getLmcode(): ?string
    {
        return $this->lmcode;
    }

    public function setLmcode(?string $lmcode): self
    {
        $this->lmcode = $lmcode;

        return $this;
    }

    public static function getStreetTypes()
    {

        $streetTypes = [
            'street' => 'Улица',
            'square' => 'Площадь',
            'clearing' => 'Просека',
            'alley' => 'Аллея',
            'boulevard' => 'Бульвар',
            'promenade' => 'Набережная',
            'lane' => 'Переулок',
            'passage' => 'Проезд',
            'avenue' => 'Проспект',
            'deadlock' => 'Тупик',
            'highway' => 'Шоссе',
        ];



        return $streetTypes;
    }

}

