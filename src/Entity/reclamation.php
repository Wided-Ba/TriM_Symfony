<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $daterec = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"La description ne peut pas être vide.")]
    #[Assert\Length(min: 7, minMessage: "La description doit contenir au moins {{ limit }} caractères.")]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"L'objet' ne peut pas être vide.")]
    #[Assert\Length(min: 3, minMessage: "L'objet' doit contenir au moins {{ limit }} caractères.")]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'idReclamations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?patient $idPatients = null;

    #[ORM\ManyToOne(inversedBy: 'idReclamation')]
    private ?admin $idAdmin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDaterec(): ?\DateTimeInterface
    {
        return $this->daterec;
    }

    public function setDaterec(\DateTimeInterface $daterec): static
    {
        $this->daterec = $daterec;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getIdPatients(): ?patient
    {
        return $this->idPatients;
    }

    public function setIdPatients(?patient $idPatients): static
    {
        $this->idPatients = $idPatients;

        return $this;
    }

    public function getIdAdmin(): ?admin
    {
        return $this->idAdmin;
    }

    public function setIdAdmin(?admin $idAdmin): static
    {
        $this->idAdmin = $idAdmin;

        return $this;
    }
}
