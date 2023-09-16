<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MedicalFile $medicalFile = null;

    #[ORM\OneToOne(inversedBy: 'reservation', cascade: ['persist', 'remove'])]
    private ?hospitalisation $hospitalisation = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?doctor $doctor = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?center $center = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getMedicalFile(): ?MedicalFile
    {
        return $this->medicalFile;
    }

    public function setMedicalFile(?MedicalFile $medicalFile): static
    {
        $this->medicalFile = $medicalFile;

        return $this;
    }

    public function getHospitalisation(): ?hospitalisation
    {
        return $this->hospitalisation;
    }

    public function setHospitalisation(?hospitalisation $hospitalisation): static
    {
        $this->hospitalisation = $hospitalisation;

        return $this;
    }

    public function getDoctor(): ?doctor
    {
        return $this->doctor;
    }

    public function setDoctor(?doctor $doctor): static
    {
        $this->doctor = $doctor;

        return $this;
    }

    public function getCenter(): ?center
    {
        return $this->center;
    }

    public function setCenter(?center $center): static
    {
        $this->center = $center;

        return $this;
    }

}