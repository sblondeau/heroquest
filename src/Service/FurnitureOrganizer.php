<?php

namespace App\Service;

use App\Entity\Furniture;
use App\Entity\Tile;
use App\Repository\TileRepository;
use App\Service\MoveService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class FurnitureOrganizer
{
    private EntityManagerInterface $entityManager;

    private TileRepository $tileRepository;

    public const DIRECTIONS = [
        'North' => [1, -1],
        'East' => [1, 1],
        'South' => [-1, 1],
        'West' => [-1, -1],
    ];

    public function __construct(EntityManagerInterface $entityManager, TileRepository $tileRepository)
    {
        $this->entityManager = $entityManager;
        $this->tileRepository = $tileRepository;
    }

    public function organize(int $startX, int $startY, Furniture $furniture)
    {
        $xIncrement =  self::DIRECTIONS[$furniture->getDirection()][0];
        $yIncrement =  self::DIRECTIONS[$furniture->getDirection()][1];

        $xSize = $furniture->getWidth();
        $ySize = $furniture->getHeight();

        if (in_array($furniture->getDirection(), ['North', 'South'])) {
            $xSize = $furniture->getHeight();
            $ySize = $furniture->getWidth();
        }
        for ($x = 0; $x < $xSize; $x++) {
            for ($y = 0; $y < $ySize; $y++) {
                $tile = $this->tileRepository->findOneBy(['x' => $startX + $x * $xIncrement, 'y' => $startY + $y * $yIncrement]);
                if (!$tile instanceof Tile) {
                    throw new Exception('Impossible to place furniture here');
                }
                $tiles[] = $tile;
            }
        }

        if (!empty($tiles)) {
            foreach ($tiles as $tile) {
                $tile->setFurniture($furniture);
                $this->entityManager->persist($tile);
            }

            $this->entityManager->flush();
        }
    }
}
