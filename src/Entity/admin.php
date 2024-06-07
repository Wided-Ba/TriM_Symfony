<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
#[ORM\Table(name: '`admin`')]
class admin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'idAdmin', targetEntity: reclamation::class)]
    private Collection $idReclamation;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\OneToMany(mappedBy: 'idAdmin', targetEntity: pharmacien::class)]
    private Collection $idPharmacien;

    #[ORM\OneToMany(mappedBy: 'idAdmin', targetEntity: chefLab::class)]
    private Collection $idChefLab;

    #[ORM\OneToMany(mappedBy: 'idAdmin', targetEntity: medecin::class)]
    private Collection $idMedecin;

    #[ORM\OneToMany(mappedBy: 'idAdmin', targetEntity: infirmier::class)]
    private Collection $idInfirmier;

    #[ORM\OneToMany(mappedBy: 'idAdmin', targetEntity: patient::class)]
    private Collection $idPatient;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $isBlocked = null;

    public function __construct()
    {
        $this->idReclamation = new ArrayCollection();
        $this->idPharmacien = new ArrayCollection();
        $this->idChefLab = new ArrayCollection();
        $this->idMedecin = new ArrayCollection();
        $this->idInfirmier = new ArrayCollection();
        $this->idPatient = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

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

    /**
     * @return Collection<int, reclamation>
     */
    public function getIdReclamation(): Collection
    {
        return $this->idReclamation;
    }

    public function addIdReclamation(reclamation $idReclamation): static
    {
        if (!$this->idReclamation->contains($idReclamation)) {
            $this->idReclamation->add($idReclamation);
            $idReclamation->setIdAdmin($this);
        }

        return $this;
    }

    public function removeIdReclamation(reclamation $idReclamation): static
    {
        if ($this->idReclamation->removeElement($idReclamation)) {
            // set the owning side to null (unless already changed)
            if ($idReclamation->getIdAdmin() === $this) {
                $idReclamation->setIdAdmin(null);
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

    /**
     * @return Collection<int, pharmacien>
     */
    public function getIdPharmacien(): Collection
    {
        return $this->idPharmacien;
    }

    public function addIdPharmacien(pharmacien $idPharmacien): static
    {
        if (!$this->idPharmacien->contains($idPharmacien)) {
            $this->idPharmacien->add($idPharmacien);
            $idPharmacien->setIdAdmin($this);
        }

        return $this;
    }

    public function removeIdPharmacien(pharmacien $idPharmacien): static
    {
        if ($this->idPharmacien->removeElement($idPharmacien)) {
            // set the owning side to null (unless already changed)
            if ($idPharmacien->getIdAdmin() === $this) {
                $idPharmacien->setIdAdmin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, chefLab>
     */
    public function getIdChefLab(): Collection
    {
        return $this->idChefLab;
    }

    public function addIdChefLab(chefLab $idChefLab): static
    {
        if (!$this->idChefLab->contains($idChefLab)) {
            $this->idChefLab->add($idChefLab);
            $idChefLab->setIdAdmin($this);
        }

        return $this;
    }

    public function removeIdChefLab(chefLab $idChefLab): static
    {
        if ($this->idChefLab->removeElement($idChefLab)) {
            // set the owning side to null (unless already changed)
            if ($idChefLab->getIdAdmin() === $this) {
                $idChefLab->setIdAdmin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, medecin>
     */
    public function getIdMedecin(): Collection
    {
        return $this->idMedecin;
    }

    public function addIdMedecin(medecin $idMedecin): static
    {
        if (!$this->idMedecin->contains($idMedecin)) {
            $this->idMedecin->add($idMedecin);
            $idMedecin->setIdAdmin($this);
        }

        return $this;
    }

    public function removeIdMedecin(medecin $idMedecin): static
    {
        if ($this->idMedecin->removeElement($idMedecin)) {
            // set the owning side to null (unless already changed)
            if ($idMedecin->getIdAdmin() === $this) {
                $idMedecin->setIdAdmin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, infirmier>
     */
    public function getIdInfirmier(): Collection
    {
        return $this->idInfirmier;
    }

    public function addIdInfirmier(infirmier $idInfirmier): static
    {
        if (!$this->idInfirmier->contains($idInfirmier)) {
            $this->idInfirmier->add($idInfirmier);
            $idInfirmier->setIdAdmin($this);
        }

        return $this;
    }

    public function removeIdInfirmier(infirmier $idInfirmier): static
    {
        if ($this->idInfirmier->removeElement($idInfirmier)) {
            // set the owning side to null (unless already changed)
            if ($idInfirmier->getIdAdmin() === $this) {
                $idInfirmier->setIdAdmin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, patient>
     */
    public function getIdPatient(): Collection
    {
        return $this->idPatient;
    }

    public function addIdPatient(patient $idPatient): static
    {
        if (!$this->idPatient->contains($idPatient)) {
            $this->idPatient->add($idPatient);
            $idPatient->setIdAdmin($this);
        }

        return $this;
    }

    public function removeIdPatient(patient $idPatient): static
    {
        if ($this->idPatient->removeElement($idPatient)) {
            // set the owning side to null (unless already changed)
            if ($idPatient->getIdAdmin() === $this) {
                $idPatient->setIdAdmin(null);
            }
        }

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
