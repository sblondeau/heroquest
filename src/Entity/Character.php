<?php

namespace App\Entity;

abstract class Character {
    abstract public function getTile(): ?Tile;
}