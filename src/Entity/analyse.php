<?php

namespace App\Entity;

use App\Repository\AnalyseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: AnalyseRepository::class)]
class analyse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le nom de l'analyse ne peut pas être vide.")]
    #[Assert\Length(min: 3, minMessage: "Le nom de l'analyse doit contenir au moins {{ limit }} caractères.")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le type de l'analyse ne peut pas être vide.")]
    #[Assert\Choice(
        choices: ["Sanguin", "Urinaire", "Fécal(e)", "Liquide céphalorachidien (LCR)", "Salivaire", "Liquide synovial", "Liquide pleural", "Liquide amniotique", "Liquide gastrique"],
        message: "Le type de l'analyse doit être l'un des choix prédéfinis."
    )]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'outillage de l'analyse ne peut pas être vide.")]
    private ?string $outillage = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Les conseils de l'analyse ne peuvent pas être vides.")]
    private ?string $conseils = null;

    #[ORM\ManyToOne]
    private ?lab $idLab = null;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getOutillage(): ?string
    {
        return $this->outillage;
    }

    public function setOutillage(string $outillage): static
    {
        $this->outillage = $outillage;

        return $this;
    }

    public function getConseils(): ?string
    {
        return $this->conseils;
    }

    public function setConseils(string $conseils): static
    {
        $this->conseils = $conseils;

        return $this;
    }

    public function getIdLab(): ?lab
    {
        return $this->idLab;
    }

    public function setIdLab(?lab $idLab): static
    {
        $this->idLab = $idLab;

        return $this;
    }
}
