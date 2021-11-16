<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class Character
{
    /**
     * @ORM\Column(type="boolean")
     */
    protected bool $hasPlayedThisTurn;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $name;

    /**
     * @ORM\Column(type="integer")
     */
    protected int $remainingMove = 0;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected int $moveDiceNumber;

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

    /**
     * Get the value of remainingMove
     */
    public function getRemainingMove(): int
    {
        return $this->remainingMove;
    }

    /**
     * Set the value of remainingMove
     */
    public function setRemainingMove(int $remainingMove): self
    {
        $this->remainingMove = $remainingMove;
        return $this;
    }

    /**
     * Get the value of moveDiceNumber
     * @return int
     */
    public function getMoveDiceNumber(): int
    {
        return $this->moveDiceNumber;
    }

    /**
     * Set the value of moveDiceNumber
     * @param int $moveDiceNumber 
     * @return self
     */
    public function setMoveDiceNumber(int $moveDiceNumber): self
    {
        $this->moveDiceNumber = $moveDiceNumber;
        return $this;
    }
}
