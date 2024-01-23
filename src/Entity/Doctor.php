<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DoctorRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DoctorRepository::class)]
class Doctor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getDoctors"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getDoctors"])]
    private ?string $gender = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getDoctors", "getSpecialties"])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getDoctors", "getSpecialties"])]
    private ?string $lastName = null;

    #[ORM\ManyToMany(targetEntity: Specialty::class, mappedBy: 'doctor')]
    #[Groups(["getDoctors"])]
    private Collection $specialties;

    #[ORM\ManyToOne(inversedBy: 'doctor')]
    #[Groups(["getDoctors"])]
    private ?Center $center = null;

    #[ORM\OneToMany(mappedBy: 'doctor', targetEntity: Reservation::class, orphanRemoval: true)]
    private Collection $reservations;

    #[ORM\Column(length: 255)]
    private ?string $emailAddress = null;


    public function __construct()
    {
        $this->specialties = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

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
            $specialty->addDoctor($this);
        }

        return $this;
    }

    public function removeSpecialty(Specialty $specialty): static
    {
        if ($this->specialties->removeElement($specialty)) {
            $specialty->removeDoctor($this);
        }

        return $this;
    }

    public function getCenter(): ?Center
    {
        return $this->center;
    }

    public function setCenter(?Center $center): static
    {
        $this->center = $center;

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
            $reservation->setDoctor($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getDoctor() === $this) {
                $reservation->setDoctor(null);
            }
        }

        return $this;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(string $emailAddress): static
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

}
