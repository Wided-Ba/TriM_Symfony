<?php

namespace App\Entity;

use App\Repository\PharmacieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PharmacieRepository::class)]
class pharmacie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $ntel = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column( )]
    private ?string $loc = null;




    #[ORM\OneToMany(mappedBy: 'idPharmacies', targetEntity: ordonnance::class)]
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

    public function getNtel(): ?int
    {
        return $this->ntel;
    }

    public function setNtel(int $ntel): static
    {
        $this->ntel = $ntel;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }


    public function getLoc(): ?string
    {
        return $this->loc;
    }

    public function setLoc(string $loc): static
    {
        $this->loc = $loc;

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
            $idOrdonnance->setIdPharmacies($this);
        }

        return $this;
    }

    public function removeIdOrdonnance(ordonnance $idOrdonnance): static
    {
        if ($this->idOrdonnances->removeElement($idOrdonnance)) {
            // set the owning side to null (unless already changed)
            if ($idOrdonnance->getIdPharmacies() === $this) {
                $idOrdonnance->setIdPharmacies(null);
            }
        }

        return $this;
    }
}