<?php

namespace App\Entity;

use App\Repository\SpecialtyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpecialtyRepository::class)]
class Specialty
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Doctor::class, inversedBy: 'specialties')]
    private Collection $doctor;

    #[ORM\ManyToMany(targetEntity: Center::class, inversedBy: 'specialties')]
    private Collection $center;

    public function __construct()
    {
        $this->doctor = new ArrayCollection();
        $this->center = new ArrayCollection();
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
        }

        return $this;
    }

    public function removeDoctor(Doctor $doctor): static
    {
        $this->doctor->removeElement($doctor);

        return $this;
    }

    /**
     * @return Collection<int, center>
     */
    public function getCenter(): Collection
    {
        return $this->center;
    }

    public function addCenter(Center $center): static
    {
        if (!$this->center->contains($center)) {
            $this->center->add($center);
        }

        return $this;
    }

    public function removeCenter(Center $center): static
    {
        $this->center->removeElement($center);

        return $this;
    }
}
