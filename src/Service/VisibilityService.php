<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\Room;
use App\Entity\Tile;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\ErrorHandler\Collecting;

class VisibilityService
{
    const DICE_FACES = 6;

    private ReachabilityService $reachabilityService;
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        ReachabilityService $reachabilityService
    ) {
        $this->entityManager = $entityManager;
        $this->reachabilityService = $reachabilityService;
    }

    /**
     * Merge Visibility of room + visibility of corridor for the current Tile
     * and set visibility to true. Once a tile is visible, it is for ever
     * But visibility != reachability (e.g. for attacks)
     */
    public function changeVisibility(Tile $tile): void
    {
        $roomTiles = $this->roomVisibility($tile);
        $corridorTiles = $this->reachabilityService->corridorReachability($tile);
        $visibleTiles = [...$roomTiles, ...$corridorTiles, $tile];
        foreach ($visibleTiles as $visibleTile) {
            $visibleTile->setVisible(true);
        }

        $this->entityManager->flush();
    }

    private function roomVisibility(Tile $tile): array
    {
        if ($tile->getRoom() instanceof Room) {
            $roomTiles = $tile->getRoom()->getTiles()->toArray();
        }

        return $roomTiles ?? [];
    }

   
}
