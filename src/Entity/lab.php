<?php

namespace App\Entity;

use App\Repository\LabRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LabRepository::class)]
class lab
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, minMessage: "Le nom doit comporter au moins {{ limit }} caractères.")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, minMessage: "L'adresse doit comporter au moins {{ limit }} caractères.")]
    private ?string $adresse = null;

    #[ORM\Column]
    #[Assert\Regex(
        pattern: '/^\d{8}$/',
        message: "Le numéro de téléphone doit comporter exactement 8 chiffres."
    )]
    private ?int $ntel = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\LessThan(propertyPath: "hfin", message: "L'heure d'ouverture doit être avant l'heure de fin.")]
    private ?\DateTimeInterface $hdebut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $hfin = null;


    #[ORM\OneToMany(mappedBy: 'idLabs', targetEntity: ordonnance::class)]
    private Collection $idOrdonnances;


    public function __construct()
    {
        $this->idOrdonnances = new ArrayCollection();
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNtel(): ?int
    {
        return $this->ntel;
    }

    public function setNtel(int $ntel): static
    {
        $this->ntel = $ntel;

        return $this;
    }

    public function getHdebut(): ?\DateTimeInterface
    {
        return $this->hdebut;
    }

    public function setHdebut(\DateTimeInterface $hdebut): static
    {
        $this->hdebut = $hdebut;

        return $this;
    }

    public function getHfin(): ?\DateTimeInterface
    {
        return $this->hfin;
    }

    public function setHfin(\DateTimeInterface $hfin): static
    {
        $this->hfin = $hfin;

        return $this;
    }


    /**
     * @return Collection<int, ordonnance>
     */
    public function getIdOrdonnances(): Collection
    {
        return $this->idOrdonnances;
    }

    public function addIdOrdonnance(ordonnance $idOrdonnance): static
    {
        if (!$this->idOrdonnances->contains($idOrdonnance)) {
            $this->idOrdonnances->add($idOrdonnance);
            $idOrdonnance->setIdLabs($this);
        }

        return $this;
    }

    public function removeIdOrdonnance(ordonnance $idOrdonnance): static
    {
        if ($this->idOrdonnances->removeElement($idOrdonnance)) {
            // set the owning side to null (unless already changed)
            if ($idOrdonnance->getIdLabs() === $this) {
                $idOrdonnance->setIdLabs(null);
            }
        }

        return $this;
    }



}
