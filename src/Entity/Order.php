<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(length: 255)]
    private ?\DateTimeImmutable $preparation_date = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?User $client = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?User $pharmacy = null;

    #[ORM\Column(type: 'json')]
    private array $orderItems = [];

    #[ORM\Column(length: 255)]
    private ?string $file = "";

    #[ORM\Column(length: 255)]
    private ?string $doctor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getPreparationDate(): ?\DateTimeImmutable
    {
        return $this->preparation_date;
    }

    public function setPreparationDate(\DateTimeImmutable $preparation_date): static
    {
        $this->preparation_date = $preparation_date;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getPharmacy(): ?User
    {
        return $this->pharmacy;
    }

    public function setPharmacy(?User $pharmacy): static
    {
        $this->pharmacy = $pharmacy;

        return $this;
    }

    public function getOrderItems(): array
    {
        return $this->orderItems;
    }

    public function setOrderItems(array $orderItems): static
    {
        $this->orderItems = $orderItems;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function getFileUrl(): ?string
    {
        return $this->file ? '/uploads/' . $this->file : null;
    }

    public function setFile(string $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getDoctor(): ?string
    {
        return $this->doctor;
    }

    public function setDoctor(string $doctor): static
    {
        $this->doctor = $doctor;

        return $this;
    }
}
