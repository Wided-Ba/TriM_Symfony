<?php

namespace App\Entity;

use App\Repository\OrdonnanceRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdonnanceRepository::class)]
class ordonnance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message:"tu dois choisir un type")]
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[Assert\NotBlank(message:"la description ne peut pas etre vide")]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\ManyToOne(inversedBy: 'idOrdonnances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?medecin $idMedecins = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?patient $idPatients = null;

    #[ORM\ManyToOne(inversedBy: 'idOrdonnances')]
    private ?pharmacie $idPharmacies = null;

    #[ORM\ManyToOne(inversedBy: 'idOrdonnances')]
    private ?lab $idLabs = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\OneToOne(inversedBy: 'ordonnance')]
    #[ORM\JoinColumn(nullable: false)]
    private ?rendezVous $idRendezVous = null;

    #[ORM\OneToOne]
    private ?analyse $analyseList = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

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

    public function getIdMedecins(): ?medecin
    {
        return $this->idMedecins;
    }

    public function setIdMedecins(?medecin $idMedecins): static
    {
        $this->idMedecins = $idMedecins;

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

    public function getIdPharmacies(): ?pharmacie
    {
        return $this->idPharmacies;
    }

    public function setIdPharmacies(?pharmacie $idPharmacies): static
    {
        $this->idPharmacies = $idPharmacies;

        return $this;
    }

    public function getIdLabs(): ?lab
    {
        return $this->idLabs;
    }

    public function setIdLabs(?lab $idLabs): static
    {
        $this->idLabs = $idLabs;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getIdRendezVous(): ?rendezVous
    {
        return $this->idRendezVous;
    }

    public function setIdRendezVous(rendezVous $idRendezVous): static
    {
        $this->idRendezVous = $idRendezVous;

        return $this;
    }

    public function getAnalyseList(): ?analyse
    {
        return $this->analyseList;
    }

    public function setAnalyseList(?analyse $analyseList): static
    {
        $this->analyseList = $analyseList;

        return $this;
    }
}
