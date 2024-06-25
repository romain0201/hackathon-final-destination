<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['message'])]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['message'])]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[Groups(['message'])]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[Groups(['message'])]
    private ?Channel $channel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getChannel(): ?Channel
    {
        return $this->channel;
    }

    public function setChannel(?Channel $channel): static
    {
        $this->channel = $channel;

        return $this;
    }
}
