<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MedicalFileRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MedicalFileRepository::class)]
class MedicalFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getMedicalFiles", "getPatients"])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMedicalFiles", "getPatients"])]
    private ?string $allergies = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $documents = null;

    #[ORM\OneToOne(mappedBy: 'medicalFile', cascade: ['persist', 'remove'])]
    private ?Patient $patient = null;

    #[ORM\OneToMany(mappedBy: 'medicalFile', targetEntity: Reservation::class, orphanRemoval: true)]
    #[Groups(["getMedicalFiles"])]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAllergies(): ?string
    {
        return $this->allergies;
    }

    public function setAllergies(?string $allergies): static
    {
        $this->allergies = $allergies;

        return $this;
    }

    public function getDocuments(): ?string
    {
        return $this->documents;
    }

    public function setDocuments(?string $documents): static
    {
        $this->documents = $documents;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        // unset the owning side of the relation if necessary
        if ($patient === null && $this->patient !== null) {
            $this->patient->setMedicalFile(null);
        }

        // set the owning side of the relation if necessary
        if ($patient !== null && $patient->getMedicalFile() !== $this) {
            $patient->setMedicalFile($this);
        }

        $this->patient = $patient;

        return $this;
    }

    /**
     * @return Collection<int, reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setMedicalFile($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getMedicalFile() === $this) {
                $reservation->setMedicalFile(null);
            }
        }

        return $this;
    }
}
