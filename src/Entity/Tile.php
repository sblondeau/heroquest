<?php

namespace App\Entity;

use App\Repository\TileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TileRepository::class)
 */
class Tile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $x;

    /**
     * @ORM\Column(type="integer")
     */
    private $y;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $north;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $east;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $south;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $west;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="tiles")
     */
    private $room;

    /**
     * @ORM\OneToOne(targetEntity=Hero::class, inversedBy="tile", cascade={"persist", "remove"})
     */
    private $occupant;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getX(): ?int
    {
        return $this->x;
    }

    public function setX(int $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?int
    {
        return $this->y;
    }

    public function setY(int $y): self
    {
        $this->y = $y;

        return $this;
    }

    public function getNorth(): ?string
    {
        return $this->north;
    }

    public function setNorth(?string $north): self
    {
        $this->north = $north;

        return $this;
    }

    public function getEast(): ?string
    {
        return $this->east;
    }

    public function setEast(?string $east): self
    {
        $this->east = $east;

        return $this;
    }

    public function getSouth(): ?string
    {
        return $this->south;
    }

    public function setSouth(?string $south): self
    {
        $this->south = $south;

        return $this;
    }

    public function getWest(): ?string
    {
        return $this->west;
    }

    public function setWest(?string $west): self
    {
        $this->west = $west;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getOccupant(): ?Hero
    {
        return $this->occupant;
    }

    public function setOccupant(?Hero $occupant): self
    {
        $this->occupant = $occupant;

        return $this;
    }
}
