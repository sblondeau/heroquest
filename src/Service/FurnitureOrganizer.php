<?php

namespace App\Service;

use App\Entity\Furniture;
use App\Entity\Tile;
use App\Repository\TileRepository;
use App\Service\MoveService;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

class FurnitureOrganizer
{
    private EntityManagerInterface $entityManager;

    private TileRepository $tileRepository;


    public function __construct(EntityManagerInterface $entityManager, TileRepository $tileRepository)
    {
        $this->entityManager = $entityManager;
        $this->tileRepository = $tileRepository;
    }

    private function setStandardOrientation(Furniture $furniture): array
    {
        for ($x = 0; $x < $furniture->getWidth(); $x++) {
            for ($y = 0; $y < $furniture->getHeight(); $y++) {
                $coordinates[] = [$x, $y];
            }
        }

        return $coordinates ?? [];
    }

    private function rotate(array $coordinates, float $angle): array
    {
        foreach ($coordinates as $coordinate) {
            [$x, $y] = $coordinate;
            $newX = $x * cos($angle) - $y * sin($angle);
            $newY = -$x * sin($angle) + $y * cos($angle);
            $newCoordinates[] = [$newX, $newY];
        }

        return $newCoordinates ?? [];
    }

    public function getStartPoint(Furniture $furniture): array
    {
        foreach ($furniture->getTiles() as $tile) {
            $xCoordinates[] = $tile->getX();
            $yCoordinates[] = $tile->getY();
        }

        return [min($xCoordinates), min($yCoordinates)];
    }


    private function hasNoWall(array $tiles)
    {
        foreach ($tiles as $tile) {
            $xCoordinates[] = $tile->getX();
            $yCoordinates[] = $tile->getY();
        }

        $startX = min($xCoordinates);
        $endX = max($xCoordinates);
        $startY = min($yCoordinates);
        $endY = max($yCoordinates);

        foreach ($tiles as $tile) {
            if (
                (($tile->getNorth() !== null) && ($tile->getY() !== $startY)) ||
                (($tile->getSouth() !== null) && ($tile->getY() !== $endY)) ||
                (($tile->getEast() !== null) && ($tile->getX() !== $endX)) ||
                (($tile->getWest() !== null) && ($tile->getX() !== $startX))
                ) {
                throw new RuntimeException('The furniture can not overlap a wall on tile '. $tile->getX() . ',' . $tile->getY());
            }
        }
    }

    private function getTilesFromCoordinates(array $coordinates, int $startX, int $startY): array
    {
        foreach ($coordinates as $coordinate) {
            [$x, $y] = $coordinate;
            $tile = $this->tileRepository->findOneBy(['x' => $x + $startX, 'y' => $y + $startY]);
            if (!$tile instanceof Tile) {
                throw new RuntimeException('You should place furniture only on tiles');
            }
            if ($tile->getFurniture() !== null) {
                throw new RuntimeException('You could not place furniture on not empty tiles');
            }

            $tiles[] = $tile;
        }

        return $tiles ?? [];
    }

    public function organize(int $startX, int $startY, Furniture $furniture)
    {
        if (!key_exists($furniture->getDirection(), MoveService::DIRECTIONS)) {
            throw new RuntimeException('Furniture direction not allowed');
        }

        $standardCoordinates = $this->setStandardOrientation($furniture);
        $rotatedCoordinates = $this->rotate($standardCoordinates, $furniture->getRotation());
        $tiles = $this->getTilesFromCoordinates($rotatedCoordinates, $startX, $startY);

        $this->hasNoWall($tiles);

        $this->save($tiles, $furniture);
    }

    private function save(array $tiles, Furniture $furniture): void
    {
        foreach ($tiles as $tile) {
            $tile->setFurniture($furniture);
            $this->entityManager->persist($tile);
        }

        $this->entityManager->flush();
    }
}
