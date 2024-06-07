<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\MaladieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity(repositoryClass: MaladieRepository::class)]
class maladie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message:"le nom ne peut pas etre vide")]
    #[Assert\Length(min: 4, minMessage: "le nom doit comporter au moins {{ limit }} caractères.")]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Assert\NotBlank(message:"le symptome ne peut pas etre vide")]
    #[Assert\Length(min: 10, minMessage: "le symptome doit comporter au moins {{ limit }} caractères.")]
    #[ORM\Column(length: 255)]
    private ?string $symptome = null;

    #[Assert\NotBlank(message:"le type ne peut pas etre vide")]
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[Assert\NotBlank(message:"la description ne peut pas etre vide")]
    #[Assert\Length(min: 4, minMessage: "la description doit comporter au moins {{ limit }} caractères.")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: patient::class, mappedBy: 'idMaladie')]
    private Collection $idPatients;

    #[ORM\ManyToOne(inversedBy: 'maladies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?medecin $id_medecin = null;

    public function __construct()
    {
        $this->idPatients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSymptome(): ?string
    {
        return $this->symptome;
    }

    public function setSymptome(string $symptome): static
    {
        $this->symptome = $symptome;

        return $this;
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

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, patient>
     */
    public function getIdPatients(): Collection
    {
        return $this->idPatients;
    }

    public function addIdPatient(patient $idPatient): static
    {
        if (!$this->idPatients->contains($idPatient)) {
            $this->idPatients->add($idPatient);
            $idPatient->addIdMaladie($this);
        }

        return $this;
    }

    public function removeIdPatient(patient $idPatient): static
    {
        if ($this->idPatients->removeElement($idPatient)) {
            $idPatient->removeIdMaladie($this);
        }

        return $this;
    }

    public function getIdMedecin(): ?medecin
    {
        return $this->id_medecin;
    }

    public function setIdMedecin(?medecin $id_medecin): static
    {
        $this->id_medecin = $id_medecin;

        return $this;
    }
}