<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PatientRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getPatients"])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups(["getPatients"])]
    private ?string $gender = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getPatients", "getReservations"])]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getPatients", "getReservations"])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getPatients"])]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getPatients"])]
    private ?string $emailAddress = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getPatients"])]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getPatients"])]
    private ?string $socialSecurity = null;

    #[ORM\OneToOne(inversedBy: 'patient', cascade: ['persist', 'remove'])]
    #[Groups(["getPatients"])]
    private ?MedicalFile $medicalFile = null;

    #[ORM\Column(nullable: true)]
    private ?bool $acceptCgu = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(["getPatients"])]
    private ?string $city = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastLogin = null;

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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $newhashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->password = $newhashedPassword;

        return $this;
    }

    public function getSocialSecurity(): ?string
    {
        return $this->socialSecurity;
    }

    public function setSocialSecurity(string $socialSecurity): static
    {
        $this->socialSecurity = $socialSecurity;

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

    public function isAcceptCgu(): ?bool
    {
        return $this->acceptCgu;
    }

    public function setAcceptCgu(?bool $acceptCgu): static
    {
        $this->acceptCgu = $acceptCgu;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): static
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }
}
