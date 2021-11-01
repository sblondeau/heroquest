<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class Character
{
    /**
     * @ORM\Column(type="boolean")
     */
    protected $hasPlayedThisTurn;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    abstract public function getTile(): ?Tile;
    
    public function getHasPlayedThisTurn(): ?bool
    {
        return $this->hasPlayedThisTurn;
    }

    public function setHasPlayedThisTurn(bool $hasPlayedThisTurn): self
    {
        $this->hasPlayedThisTurn = $hasPlayedThisTurn;

        return $this;
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
}
