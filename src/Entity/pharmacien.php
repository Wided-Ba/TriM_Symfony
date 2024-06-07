<?php

namespace App\Entity;

use App\Repository\PharmacienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PharmacienRepository::class)]
class pharmacien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column]
    private ?int $ntel = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;
    #[ORM\Column(length: 255)]
    private ?string $genre = null;

    #[ORM\ManyToOne(inversedBy: 'idPharmacien')]
    private ?admin $idAdmin = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: True)]
    private ?pharmacie $idPharmacie = null;

    #[ORM\OneToMany(mappedBy: 'idPharmacien', targetEntity: medicament::class)]
    private Collection $idMedicament;

    #[ORM\Column]
    private ?bool $isBlocked = null;

    public function __construct()
    {
        $this->idMedicament = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;

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

    public function getIdPharmacie(): ?pharmacie
    {
        return $this->idPharmacie;
    }

    public function setIdPharmacie(pharmacie $idPharmacie): static
    {
        $this->idPharmacie = $idPharmacie;

        return $this;
    }

    /**
     * @return Collection<int, medicament>
     */
    public function getIdMedicament(): Collection
    {
        return $this->idMedicament;
    }

    public function addIdMedicament(medicament $idMedicament): static
    {
        if (!$this->idMedicament->contains($idMedicament)) {
            $this->idMedicament->add($idMedicament);
            $idMedicament->setIdPharmacien($this);
        }

        return $this;
    }

    public function removeIdMedicament(medicament $idMedicament): static
    {
        if ($this->idMedicament->removeElement($idMedicament)) {
            // set the owning side to null (unless already changed)
            if ($idMedicament->getIdPharmacien() === $this) {
                $idMedicament->setIdPharmacien(null);
            }
        }

        return $this;
    }

    public function isIsBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(bool $isBlocked): static
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }
}
