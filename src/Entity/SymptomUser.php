<?php

namespace App\Entity;

use App\Repository\SymptomUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SymptomUserRepository::class)]
class SymptomUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'symptomUsers')]
    #[Groups(['symptom'])]
    private ?Symptom $symptom = null;

    #[ORM\ManyToOne(inversedBy: 'symptomUsers')]
    #[Groups(['symptom'])]
    private ?User $patient = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['symptom'])]
    private ?bool $archived = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSymptom(): ?Symptom
    {
        return $this->symptom;
    }

    public function setSymptom(?Symptom $symptom): static
    {
        $this->symptom = $symptom;

        return $this;
    }

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(?bool $archived): static
    {
        $this->archived = $archived;

        return $this;
    }
}
