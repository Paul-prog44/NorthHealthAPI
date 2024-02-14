<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CenterRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CenterRepository::class)]
class Center
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getCenters"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getCenters", "getDoctors", "getReservations"])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getCenters"])]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getCenters"])]
    private ?string $country = null;

    #[ORM\ManyToMany(targetEntity: Specialty::class, mappedBy: 'center')]
    #[Groups(["getCenters"])]
    private Collection $specialties;

    #[ORM\OneToMany(mappedBy: 'center', targetEntity: Doctor::class, orphanRemoval: true)] //orphanRemoval permet le suppression des entité enfant en cas de suppression de l'entité parente
    #[Groups(["getCenters"])]
    private Collection $doctor;

    #[ORM\OneToMany(mappedBy: 'center', targetEntity: Reservation::class, orphanRemoval: true)]
    private Collection $reservations;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getCenters"])]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getCenters"])]
    private ?string $imageFileName = null;

    public function __construct()
    {
        $this->specialties = new ArrayCollection();
        $this->doctor = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, Specialty>
     */
    public function getSpecialties(): Collection
    {
        return $this->specialties;
    }

    public function addSpecialty(Specialty $specialty): static
    {
        if (!$this->specialties->contains($specialty)) {
            $this->specialties->add($specialty);
            $specialty->addCenter($this);
        }

        return $this;
    }

    public function removeSpecialty(Specialty $specialty): static
    {
        if ($this->specialties->removeElement($specialty)) {
            $specialty->removeCenter($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, doctor>
     */
    public function getDoctor(): Collection
    {
        return $this->doctor;
    }

    public function addDoctor(Doctor $doctor): static
    {
        if (!$this->doctor->contains($doctor)) {
            $this->doctor->add($doctor);
            $doctor->setCenter($this);
        }

        return $this;
    }

    public function removeDoctor(Doctor $doctor): static
    {
        if ($this->doctor->removeElement($doctor)) {
            // set the owning side to null (unless already changed)
            if ($doctor->getCenter() === $this) {
                $doctor->setCenter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setCenter($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getCenter() === $this) {
                $reservation->setCenter(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getImageFileName(): ?string
    {
        return $this->imageFileName;
    }

    public function setImageFileName(?string $imageFileName): static
    {
        $this->imageFileName = $imageFileName;

        return $this;
    }
}
