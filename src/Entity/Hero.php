<?php

namespace App\Entity;

use App\Repository\HeroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HeroRepository::class)
 */
class Hero extends Character
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
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=Tile::class, mappedBy="occupant", cascade={"persist", "remove"})
     */
    private $tile;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function getTile(): ?Tile
    {
        return $this->tile;
    }

    public function setTile(?Tile $tile): self
    {
        // unset the owning side of the relation if necessary
        if ($tile === null && $this->tile !== null) {
            $this->tile->setOccupant(null);
        }

        // set the owning side of the relation if necessary
        if ($tile !== null && $tile->getOccupant() !== $this) {
            $tile->setOccupant($this);
        }

        $this->tile = $tile;

        return $this;
    }
}
