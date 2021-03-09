<?php

namespace App\Entity;

use App\Repository\FurnitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FurnitureRepository::class)
 */
class Furniture
{
    const ANGLES = [
        'North' => (M_PI / 2),
        'West' => 0,
        'East' => M_PI,
        'South' => (M_PI / -2),
    ];

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
     * @ORM\Column(type="integer")
     */
    private $width;

    /**
     * @ORM\Column(type="integer")
     */
    private $height;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $direction;

    /**
     * @ORM\OneToMany(targetEntity=Tile::class, mappedBy="furniture")
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function getRotation(): float
    {
        return self::ANGLES[$this->getDirection()];
    }
    
    public function setDirection(string $direction): self
    {
        $this->direction = $direction;

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
            $tile->setFurniture($this);
        }

        return $this;
    }

    public function removeTile(Tile $tile): self
    {
        if ($this->tiles->removeElement($tile)) {
            // set the owning side to null (unless already changed)
            if ($tile->getFurniture() === $this) {
                $tile->setFurniture(null);
            }
        }

        return $this;
    }
}
