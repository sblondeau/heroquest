<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoomRepository::class)
 */
class Room
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
    private $color;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVisited;

    /**
     * @ORM\OneToMany(targetEntity=Tile::class, mappedBy="room")
     */
    private $tiles;

    public function __construct()
    {
        $this->tiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getIsVisited(): ?bool
    {
        return $this->isVisited;
    }

    public function setIsVisited(bool $isVisited): self
    {
        $this->isVisited = $isVisited;

        return $this;
    }

    /**
     * @return Collection|Tile[]
     */
    public function getTiles(): Collection
    {
        return $this->tiles;
    }

    public function addTile(Tile $tile): self
    {
        if (!$this->tiles->contains($tile)) {
            $this->tiles[] = $tile;
            $tile->setRoom($this);
        }

        return $this;
    }

    public function removeTile(Tile $tile): self
    {
        if ($this->tiles->removeElement($tile)) {
            // set the owning side to null (unless already changed)
            if ($tile->getRoom() === $this) {
                $tile->setRoom(null);
            }
        }

        return $this;
    }
}
