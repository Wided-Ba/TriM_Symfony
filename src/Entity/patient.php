<?php

namespace App\Entity;
use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $Prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $Age = null;

    #[ORM\Column]
    private ?int $Ntel = null;

    #[ORM\Column(length: 255)]
    private ?string $Email = null;

    #[ORM\Column(length: 255)]
    private ?string $Password = null;

    #[ORM\Column(length: 255)]
    private ?string $Adresse = null;

    #[ORM\ManyToMany(targetEntity: maladie::class, inversedBy: 'idPatients')]
    private Collection $idMaladie;

    #[ORM\OneToMany(mappedBy: 'idPatients', targetEntity: reclamation::class)]
    private Collection $idReclamations;

    #[ORM\OneToMany(mappedBy: 'idPatients', targetEntity: rendezVous::class)]
    private Collection $idRv;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\Column(length: 255)]
    private ?string $genre = null;

    #[ORM\ManyToOne(inversedBy: 'idPatient')]
    private ?admin $idAdmin = null;

    #[ORM\Column]
    private ?bool $isBlocked = null;

    public function __construct()
    {
        $this->idMaladie = new ArrayCollection();
        $this->idReclamations = new ArrayCollection();
        $this->idRv = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): static
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->Age;
    }

    public function setAge(string $Age): static
    {
        $this->Age = $Age;

        return $this;
    }

    public function getNtel(): ?int
    {
        return $this->Ntel;
    }

    public function setNtel(int $Ntel): static
    {
        $this->Ntel = $Ntel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): static
    {
        $this->Password = $Password;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): static
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    /**
     * @return Collection<int, maladie>
     */
    public function getIdMaladie(): Collection
    {
        return $this->idMaladie;
    }

    public function addIdMaladie(maladie $idMaladie): static
    {
        if (!$this->idMaladie->contains($idMaladie)) {
            $this->idMaladie->add($idMaladie);
        }

        return $this;
    }

    public function removeIdMaladie(maladie $idMaladie): static
    {
        $this->idMaladie->removeElement($idMaladie);

        return $this;
    }

    /**
     * @return Collection<int, reclamation>
     */
    public function getIdReclamations(): Collection
    {
        return $this->idReclamations;
    }

    public function addIdReclamation(reclamation $idReclamation): static
    {
        if (!$this->idReclamations->contains($idReclamation)) {
            $this->idReclamations->add($idReclamation);
            $idReclamation->setIdPatients($this);
        }

        return $this;
    }

    public function removeIdReclamation(reclamation $idReclamation): static
    {
        if ($this->idReclamations->removeElement($idReclamation)) {
            // set the owning side to null (unless already changed)
            if ($idReclamation->getIdPatients() === $this) {
                $idReclamation->setIdPatients(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, rendezVous>
     */
    public function getIdRv(): Collection
    {
        return $this->idRv;
    }

    public function addIdRv(rendezVous $idRv): static
    {
        if (!$this->idRv->contains($idRv)) {
            $this->idRv->add($idRv);
            $idRv->setIdPatients($this);
        }

        return $this;
    }

    public function removeIdRv(rendezVous $idRv): static
    {
        if ($this->idRv->removeElement($idRv)) {
            // set the owning side to null (unless already changed)
            if ($idRv->getIdPatients() === $this) {
                $idRv->setIdPatients(null);
            }
        }

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
