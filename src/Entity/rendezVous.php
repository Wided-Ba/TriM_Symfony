<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class rendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heure = null;


    #[ORM\Column(length: 255)]
    private ?string $motif = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'idRv')]
    #[ORM\JoinColumn(nullable: false)]
    private ?patient $idPatients = null;

    #[ORM\ManyToOne(inversedBy: 'idRv')]
    #[ORM\JoinColumn(nullable: false)]
    private ?medecin $idMedecins = null;

    #[ORM\OneToOne(mappedBy: 'idRendezVous', cascade: ['persist', 'remove'])]
    private ?ordonnance $ordonnance = null;

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

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): static
    {
        $this->heure = $heure;

        return $this;
    }


    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): static
    {
        $this->motif = $motif;

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

    public function getIdMedecins(): ?medecin
    {
        return $this->idMedecins;
    }

    public function setIdMedecins(?medecin $idMedecins): static
    {
        $this->idMedecins = $idMedecins;

        return $this;
    }

    public function getOrdonnance(): ?ordonnance
    {
        return $this->ordonnance;
    }

    public function setOrdonnance(ordonnance $ordonnance): static
    {
        // set the owning side of the relation if necessary
        if ($ordonnance->getIdRendezVous() !== $this) {
            $ordonnance->setIdRendezVous($this);
        }

        $this->ordonnance = $ordonnance;

        return $this;
    }
}
