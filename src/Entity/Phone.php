<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhoneRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity("model")
 */
class Phone
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({
     *      "getAllPhones",
     *      "getOnePhone"
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Groups({
     *      "getAllPhones",
     *      "getOnePhone"
     * })
     */
    private $price;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({
     *      "getOnePhone"
     * })
     */
    private $DAS;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Groups({
     *      "getAllPhones",
     *      "getOnePhone"
     * })
     */
    private $model;

    /**
     * datetime of persisting in database
     * 
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * date of release, nullable so we can add a phone before anyone can have it
     * 
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({
     *      "getOnePhone"
     * })
     */
    private $releaseDate;

    /**
     * in inches
     * 
     * @ORM\Column(type="float")
     * @Groups({
     *      "getOnePhone"
     * })
     */
    private $screenSize;

    /**
     * 3G, 4G, 5G ...
     * 
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *      "getOnePhone"
     * })
     */
    private $connexionType;

    /**
     * cm ?
     * 
     * @ORM\Column(type="float")
     * @Groups({
     *      "getOnePhone"
     * })
     */
    private $width;

    /**
     * @ORM\Column(type="float")
     * @Groups({
     *      "getOnePhone"
     * })
     */
    private $height;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({
     *      "getOnePhone"
     * })
     */
    private $thickness;

    /**
     * grammes ?
     * 
     * @ORM\Column(type="float")
     * @Groups({
     *      "getOnePhone"
     * })
     */
    private $weight;

    /**
     * number of pixels width
     * 
     * @ORM\Column(type="integer")
     * @Groups({
     *      "getOnePhone"
     * })
     */
    private $pixelsX;

    /**
     * pixels height
     * 
     * @ORM\Column(type="integer")
     * @Groups({
     *      "getOnePhone"
     * })
     */
    private $pixelsY;

    /**
     * ex : 3 cameras, 13MPx portait, 20MPx grand angle, 8MPx zoom, filme en 4K
     * 
     * @ORM\Column(type="text", nullable=true)
     * @Groups({
     *      "getOnePhone"
     * })
     */
    private $camerasSpecifications;

    /**
     * in hours
     * 
     * @ORM\Column(type="float")
     * @Groups({
     *      "getOnePhone"
     * })
     */
    private $batteryTime;

    /**
     * this way we can add specifications
     * 
     * @ORM\Column(type="text", nullable=true)
     * @Groups({
     *      "getOnePhone"
     * })
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({
     *      "getOnePhone"
     * })
     */
    private $color;


    /**
     * @ORM\PrePersist
     */
    public function prepare()
    {
        if (empty($this->createdAt))
            $this->createdAt = new \DateTime();
    }

    public function __toString()
    {
        return $this->model();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDAS(): ?float
    {
        return $this->DAS;
    }

    public function setDAS(?float $DAS): self
    {
        $this->DAS = $DAS;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getScreenSize(): ?float
    {
        return $this->screenSize;
    }

    public function setScreenSize(float $screenSize): self
    {
        $this->screenSize = $screenSize;

        return $this;
    }

    public function getConnexionType(): ?string
    {
        return $this->connexionType;
    }

    public function setConnexionType(string $connexionType): self
    {
        $this->connexionType = $connexionType;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(float $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getThickness(): ?float
    {
        return $this->thickness;
    }

    public function setThickness(?float $thickness): self
    {
        $this->thickness = $thickness;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getPixelsX(): ?int
    {
        return $this->pixelsX;
    }

    public function setPixelsX(int $pixelsX): self
    {
        $this->pixelsX = $pixelsX;

        return $this;
    }

    public function getPixelsY(): ?int
    {
        return $this->pixelsY;
    }

    public function setPixelsY(int $pixelsY): self
    {
        $this->pixelsY = $pixelsY;

        return $this;
    }

    public function getCamerasSpecifications(): ?string
    {
        return $this->camerasSpecifications;
    }

    public function setCamerasSpecifications(?string $camerasSpecifications): self
    {
        $this->camerasSpecifications = $camerasSpecifications;

        return $this;
    }

    public function getBatteryTime(): ?float
    {
        return $this->batteryTime;
    }

    public function setBatteryTime(float $batteryTime): self
    {
        $this->batteryTime = $batteryTime;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }



    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
