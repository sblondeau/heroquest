<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\Furniture;
use App\Entity\Tile;
use App\Repository\TileRepository;
use Doctrine\ORM\EntityManagerInterface;
use OutOfRangeException;
use RangeException;
use RuntimeException;

class MoveService
{
    public const DIRECTIONS = ['North' => [0, -1], 'East' => [1, 0], 'South' => [0, 1], 'West' => [-1, 0]];
    public const INVERSED_DIRECTIONS = ['North' => 'South', 'East' => 'West', 'South' => 'North', 'West' => 'East'];

    private $tileRepository;

    private $entityManager;

    public function __construct(TileRepository $tileRepository, EntityManagerInterface $entityManager)
    {
        $this->tileRepository = $tileRepository;
        $this->entityManager = $entityManager;
    }

    public function move(Character $character, string $direction)
    {
        [$xModifier, $yModifier] = self::DIRECTIONS[$direction];
        $tile = $character->getTile();

        $destinationTile = $this->tileRepository->findOneBy([
            'x' => $tile->getX() + $xModifier,
            'y' => $tile->getY() + $yModifier
        ]);
        if (!$destinationTile instanceof Tile) {
            throw new OutOfRangeException('Impossible move');
        }
        
        if ($this->isNotFree($tile, $destinationTile, $direction) === true) {
            throw new RuntimeException('The way is not free');
        }

        $destinationTile->setOccupant($character);
        $tile->setOccupant(null);
        $character->setHasPlayedThisTurn(1);
        $this->entityManager->persist($character);
        $this->entityManager->persist($tile);
        $this->entityManager->flush();
    }

    private function isNotFree(Tile $tile, Tile $destinationTile, string $direction)
    {
        $getDirection = 'get'. $direction;

        $getInversedDirection = 'get'. self::INVERSED_DIRECTIONS[$direction];
        return
            $destinationTile->getOccupant() !== null ||
            $destinationTile->getFurniture() instanceof Furniture ||
            in_array($tile->$getDirection(), ['wall', 'door']) ||
            in_array($destinationTile->$getInversedDirection(), ['wall', 'door']);
    }
}
