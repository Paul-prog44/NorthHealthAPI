<?php

namespace App\Entity;

use App\Repository\HospitalisationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HospitalisationRepository::class)]
class Hospitalisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getHospitalisations"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getHospitalisations"])]
    private ?string $type = null;

    #[ORM\Column]
    #[Groups(["getHospitalisations"])]
    private ?bool $vegetarian = null;

    #[ORM\Column]
    #[Groups(["getHospitalisations"])]
    private ?bool $singleRoom = null;

    #[ORM\Column]
    #[Groups(["getHospitalisations"])]
    private ?bool $television = null;

    #[ORM\OneToOne(mappedBy: 'hospitalisation', cascade: ['persist', 'remove'])]
    private ?Reservation $reservation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function isVegetarian(): ?bool
    {
        return $this->vegetarian;
    }

    public function setVegetarian(bool $vegetarian): static
    {
        $this->vegetarian = $vegetarian;

        return $this;
    }

    public function isSingleRoom(): ?bool
    {
        return $this->singleRoom;
    }

    public function setSingleRoom(bool $singleRoom): static
    {
        $this->singleRoom = $singleRoom;

        return $this;
    }

    public function isTelevision(): ?bool
    {
        return $this->television;
    }

    public function setTelevision(bool $television): static
    {
        $this->television = $television;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        // unset the owning side of the relation if necessary
        if ($reservation === null && $this->reservation !== null) {
            $this->reservation->setHospitalisation(null);
        }

        // set the owning side of the relation if necessary
        if ($reservation !== null && $reservation->getHospitalisation() !== $this) {
            $reservation->setHospitalisation($this);
        }

        $this->reservation = $reservation;

        return $this;
    }
}
