<?php

namespace App\Entity;

use App\Repository\AlbumRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AlbumRepository::class)
 */
class Album
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $Annee;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Genre;

    /**
     * @ORM\ManyToOne(targetEntity=Artiste::class, inversedBy="albums")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Artiste;

    public function __toString(): ?string
    {
        return $this->Nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->Annee;
    }

    public function setAnnee(int $Annee): self
    {
        $this->Annee = $Annee;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->Genre;
    }

    public function setGenre(?string $Genre): self
    {
        $this->Genre = $Genre;

        return $this;
    }

    public function getArtiste(): ?Artiste
    {
        return $this->Artiste;
    }

    public function setArtiste(?Artiste $Artiste): self
    {
        $this->Artiste = $Artiste;

        return $this;
    }
}
