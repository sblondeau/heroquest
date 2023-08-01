<?php

namespace App\Service;

use App\Entity\Tile;
use App\Repository\TileRepository;

class ReachabilityService
{
    private TileRepository $tileRepository;

    private array $reachableTiles = [];

    public function __construct(TileRepository $tileRepository)
    {
        $this->tileRepository = $tileRepository;
    }

    /**
     * Get all tiles and calculate distances and angles from current tile
     */
    private function initializeReachableTiles(Tile $currentTile): void
    {
        $tiles = $this->tileRepository->findAll();
        foreach ($tiles as $tile) {
            // TODO manage intemediate status (visible once but not reachable)
            $tile->setVisible(false);
            $this->reachableTiles[$tile->getId()] = [
                'distance' => $this->tileDistance($currentTile, $tile),
                'angle' => $this->tileAngle($currentTile, $tile),
                'reachable' => true,
            ];
        }
    }

    public function corridorReachability(Tile $currentTile): array
    {
        if ($currentTile->getRoom() !== null) {
            return [];
        }

        $this->initializeReachableTiles($currentTile);

        $maskingTiles = $this->getMaskingTilesInfos($currentTile);
 
        foreach ($maskingTiles as $maskingKey => $maskingTile) {
            $this->reachableTiles[$maskingKey]['reachable'] = false;
            foreach ($this->reachableTiles as $reachableTileKey => $reachableTile) {
                if (
                    $reachableTile['reachable'] &&
                    $reachableTile['angle'] > $maskingTile['startAngle'] &&
                    $reachableTile['angle'] < $maskingTile['endAngle'] &&
                    $reachableTile['distance'] > $this->reachableTiles[$maskingKey]['distance']
                ) {
                    $this->reachableTiles[$reachableTileKey]['reachable'] = false;
                }
            }
        }
        // $this->reachableTiles[$currentTile->getId()]['reachable'] = true;
        foreach ($this->reachableTiles as $reachableTileKey => $reachableTile) {
            if ($reachableTile['reachable']) {
                $visibleTiles[] = $this->tileRepository->find($reachableTileKey);
            }
        }

        return $visibleTiles ?? [];
    }

    /**
     * Get distance beetween two tiles
     */
    public function tileDistance(Tile $from, Tile $to): float
    {
        return MathService::distance($from->getX(), $from->getY(), $to->getX(), $to->getY());
    }

    /**
     * Get angle beetwen two tiles
     */
    public function tileAngle(Tile $from, Tile $to): float
    {
        return MathService::angle($from->getX(), $from->getY(), $to->getX(), $to->getY());
    }

    /**
     * Get all tiles masked by another not visible tile (room, monster...)
     */
    private function getMaskingTilesInfos(Tile $currentTile): array
    {
        $fromX = $currentTile->getX() + 0.5;
        $fromY = $currentTile->getY() + 0.5;

        $maskingTiles = $this->tileRepository->findMaskingTiles();

        foreach ($maskingTiles as $maskingTile) {
            [$cornerStartX, $cornerStartY, $cornerEndX, $cornerEndY] = $this->getMaskingCorners($currentTile, $maskingTile);
            $firstAngle = MathService::angle($fromX, $fromY, $cornerStartX, $cornerStartY);
            $lastAngle = MathService::angle($fromX, $fromY, $cornerEndX, $cornerEndY);
            $maskingAngles[$maskingTile->getId()] = [
                'tile' => $maskingTile,
                'startAngle' => $firstAngle,
                'endAngle' => $lastAngle,
                'coords' => [$maskingTile->getX(), $maskingTile->getY()]
            ];
        }

        return $maskingAngles;
    }

    /**
     * Get corners coords according to the orientation between the current tile and the masking tile
     */
    private function getMaskingCorners(Tile $currentTile, Tile $maskingTile): array
    {
        //cornerStartX, $cornerStartY, $cornerEndX, $cornerEndY
        $coeffs = [0, 0, 0, 0];
        // M * 
        // * C
        if ($currentTile->getX() > $maskingTile->getX() && $currentTile->getY() > $maskingTile->getY()) {
            $coeffs = [1, 0, 0, 1];
        }
        // * C 
        // M *
        if ($currentTile->getX() > $maskingTile->getX() && $currentTile->getY() < $maskingTile->getY()) {
            $coeffs = [0, 0, 1, 1];
        }
        // * M 
        // C *
        if ($currentTile->getX() < $maskingTile->getX() && $currentTile->getY() > $maskingTile->getY()) {
            $coeffs = [1, 1, 0, 0];
        }
        // C * 
        // * M
        if ($currentTile->getX() < $maskingTile->getX() && $currentTile->getY() < $maskingTile->getY()) {
            $coeffs = [0, 1, 1, 0];
        }
        // M 
        // C
        if ($currentTile->getX() === $maskingTile->getX() && $currentTile->getY() > $maskingTile->getY()) {
            $coeffs = [1, 1, 0, 1];
        }
        // C
        // M 
        if ($currentTile->getX() === $maskingTile->getX() && $currentTile->getY() < $maskingTile->getY()) {
            $coeffs = [0, 0, 1, 0];
        }
        // C M 
        if ($currentTile->getX() < $maskingTile->getX() && $currentTile->getY() === $maskingTile->getY()) {
            $coeffs = [0, 1, 0, 0];
        }
        // M C 
        if ($currentTile->getX() > $maskingTile->getX() && $currentTile->getY() === $maskingTile->getY()) {
            $coeffs = [1, 0, 1, 1];
        }

        return [
            $maskingTile->getX() + $coeffs[0] / 2,
            $maskingTile->getY() + $coeffs[1] / 2,
            $maskingTile->getX() + $coeffs[2] / 2,
            $maskingTile->getY() + $coeffs[3] / 2,
        ];
    }

    public function isReachable(Tile $tile): bool
    {
        return $this->reachableTiles[$tile->getId()]['reachable'];
    }
}
