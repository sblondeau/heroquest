<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\Room;
use App\Entity\Tile;
use App\Repository\RoomRepository;
use App\Repository\TileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\ErrorHandler\Collecting;

class VisibilityService
{
    const DICE_FACES = 6;

    private TileRepository $tileRepository;
    private RoomRepository $roomRepository;
    private ReachabilityService $reachabilityService;
    private EntityManagerInterface $entityManager;

    public function __construct(
        TileRepository $tileRepository,
        EntityManagerInterface $entityManager,
        RoomRepository $roomRepository,
        ReachabilityService $reachabilityService
    ) {
        $this->tileRepository = $tileRepository;
        $this->roomRepository = $roomRepository;
        $this->entityManager = $entityManager;
        $this->reachabilityService = $reachabilityService;
    }

    public function changeVisibility(Tile $tile): void
    {
        $roomTiles = $this->roomVisibility($tile);
        $corridorTiles = $this->reachabilityService->corridorVisibility($tile);
        $visibleTiles = array_merge($roomTiles, $corridorTiles);
        $visibleTiles[] = $tile;
        foreach ($visibleTiles as $visibleTile) {
            $visibleTile->setVisible(true);
        }
    }

    private function roomVisibility(Tile $tile): array
    {
        if ($tile->getRoom() instanceof Room) {
            $roomTiles = $tile->getRoom()->getTiles()->toArray();
        }

        return $roomTiles ?? [];
    }

   
}
