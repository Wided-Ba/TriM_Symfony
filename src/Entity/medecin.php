<?php

namespace App\Entity;

use App\Repository\MedecinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedecinRepository::class)]
class medecin
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
    private ?int $nTel = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $Adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $specialite = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $hdebut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $hfin = null;

    #[ORM\ManyToMany(targetEntity: patient::class)]
    private Collection $idPatients;

    #[ORM\OneToMany(mappedBy: 'idMedecins', targetEntity: rendezVous::class)]
    private Collection $idRv;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\Column(length: 255)]
    private ?string $genre = null;

    #[ORM\ManyToOne(inversedBy: 'idMedecin')]
    private ?admin $idAdmin = null;

    #[ORM\OneToMany(mappedBy: 'idMedecins', targetEntity: ordonnance::class)]
    private Collection $idOrdonnances;

    #[ORM\OneToMany(mappedBy: 'id_medecin', targetEntity: maladie::class)]
    private Collection $maladies;

    #[ORM\Column]
    private ?bool $isBlocked = null;

    public function __construct()
    {
        $this->idPatients = new ArrayCollection();
        $this->idRv = new ArrayCollection();
        $this->idOrdonnances = new ArrayCollection();
        $this->maladies = new ArrayCollection();
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

    public function getNTel(): ?int
    {
        return $this->nTel;
    }

    public function setNTel(int $nTel): static
    {
        $this->nTel = $nTel;

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

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): static
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): static
    {
        $this->specialite = $specialite;

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

    public function gethfin(): ?\DateTimeInterface
    {
        return $this->hfin;
    }

    public function sethfin(\DateTimeInterface $hfin): static
    {
        $this->hfin = $hfin;

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
        }

        return $this;
    }

    public function removeIdPatient(patient $idPatient): static
    {
        $this->idPatients->removeElement($idPatient);

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
            $idRv->setIdMedecins($this);
        }

        return $this;
    }

    public function removeIdRv(rendezVous $idRv): static
    {
        if ($this->idRv->removeElement($idRv)) {
            // set the owning side to null (unless already changed)
            if ($idRv->getIdMedecins() === $this) {
                $idRv->setIdMedecins(null);
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
            $idOrdonnance->setIdMedecins($this);
        }

        return $this;
    }

    public function removeIdOrdonnance(ordonnance $idOrdonnance): static
    {
        if ($this->idOrdonnances->removeElement($idOrdonnance)) {
            // set the owning side to null (unless already changed)
            if ($idOrdonnance->getIdMedecins() === $this) {
                $idOrdonnance->setIdMedecins(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Maladie>
     */
    public function getMaladies(): Collection
    {
        return $this->maladies;
    }

    public function addMalady(Maladie $malady): static
    {
        if (!$this->maladies->contains($malady)) {
            $this->maladies->add($malady);
            $malady->setIdMedecin($this);
        }

        return $this;
    }

    public function removeMalady(Maladie $malady): static
    {
        if ($this->maladies->removeElement($malady)) {
            // set the owning side to null (unless already changed)
            if ($malady->getIdMedecin() === $this) {
                $malady->setIdMedecin(null);
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
