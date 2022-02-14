<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 */
class Car
{
    const STATE_ENABLE = 1; // Etat de la voiture sur affiché
    const STATE_DISABLE = 0; // Etat de la voiture sur caché
    const STATE_RENTALE = 2; // Voiture louer ou non

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $model;

    /**
     * @ORM\OneToMany(targetEntity=Rental::class, mappedBy="car")
     */
    private $rentals;

    /**
     * @ORM\Column(type="integer")
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numberPlate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Picture2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture3;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="cars")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity=Advert::class, mappedBy="Car")
     */
    private $adverts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture1_origFileName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture2_origFileName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture3_origFileName;

    public function __construct()
    {
        $this->rentals = new ArrayCollection();
        $this->state =  self::STATE_ENABLE; // Met l'état sur 1 à la création
        $this->adverts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

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

    /**
     * @return Collection|Rental[]
     */
    public function getRentals(): Collection
    {
        return $this->rentals;
    }

    public function addRental(Rental $rental): self
    {
        if (!$this->rentals->contains($rental)) {
            $this->rentals[] = $rental;
            $rental->setCar($this);
        }

        return $this;
    }

    public function removeRental(Rental $rental): self
    {
        if ($this->rentals->removeElement($rental)) {
            // set the owning side to null (unless already changed)
            if ($rental->getCar() === $this) {
                $rental->setCar(null);
            }
        }

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getNumberPlate(): ?string
    {
        return $this->numberPlate;
    }

    public function setNumberPlate(string $numberPlate): self
    {
        $this->numberPlate = $numberPlate;

        return $this;
    }

    public function getPicture1()
    {
        return $this->picture1;
    }

    public function setPicture1($picture1): self
    {
        $this->picture1 = $picture1;

        return $this;
    }

    public function getPicture2()
    {
        return $this->Picture2;
    }

    public function setPicture2($Picture2): self
    {
        $this->Picture2 = $Picture2;

        return $this;
    }

    public function getPicture3()
    {
        return $this->picture3;
    }

    public function setPicture3($picture3): self
    {
        $this->picture3 = $picture3;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection|Advert[]
     */
    public function getAdverts(): Collection
    {
        return $this->adverts;
    }

    public function addAdvert(Advert $advert): self
    {
        if (!$this->adverts->contains($advert)) {
            $this->adverts[] = $advert;
            $advert->setCar($this);
        }

        return $this;
    }

    public function removeAdvert(Advert $advert): self
    {
        if ($this->adverts->removeElement($advert)) {
            // set the owning side to null (unless already changed)
            if ($advert->getCar() === $this) {
                $advert->setCar(null);
            }
        }

        return $this;
    }

    public function getPicture1OrigFileName(): ?string
    {
        return $this->picture1_origFileName;
    }

    public function setPicture1OrigFileName(string $picture1_origFileName): self
    {
        $this->picture1_origFileName = $picture1_origFileName;

        return $this;
    }

    public function getPicture2OrigFileName(): ?string
    {
        return $this->picture2_origFileName;
    }

    public function setPicture2OrigFileName(?string $picture2_origFileName): self
    {
        $this->picture2_origFileName = $picture2_origFileName;

        return $this;
    }

    public function getPicture3OrigFileName(): ?string
    {
        return $this->picture3_origFileName;
    }

    public function setPicture3OrigFileName(string $picture3_origFileName): self
    {
        $this->picture3_origFileName = $picture3_origFileName;

        return $this;
    }
}
