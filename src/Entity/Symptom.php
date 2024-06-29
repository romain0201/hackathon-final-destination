<?php

namespace App\Entity;

use App\Repository\SymptomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SymptomRepository::class)]
class Symptom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['symptom'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['symptom'])]
    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[Groups(['symptom'])]
    #[ORM\Column]
    private ?bool $isActive = null;

    /**
     * @var Collection<int, SymptomUser>
     */
    #[ORM\OneToMany(targetEntity: SymptomUser::class, mappedBy: 'symptom')]
    #[Groups(['symptom'])]
    private Collection $symptomUsers;

    public function __construct()
    {
        $this->symptomUsers = new ArrayCollection();
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection<int, SymptomUser>
     */
    public function getSymptomUsers(): Collection
    {
        return $this->symptomUsers;
    }

    public function addSymptomUser(SymptomUser $symptomUser): static
    {
        if (!$this->symptomUsers->contains($symptomUser)) {
            $this->symptomUsers->add($symptomUser);
            $symptomUser->setSymptom($this);
        }

        return $this;
    }

    public function removeSymptomUser(SymptomUser $symptomUser): static
    {
        if ($this->symptomUsers->removeElement($symptomUser)) {
            // set the owning side to null (unless already changed)
            if ($symptomUser->getSymptom() === $this) {
                $symptomUser->setSymptom(null);
            }
        }

        return $this;
    }
}
