<?php

namespace App\Service;

use App\Entity\Tile;
use App\Repository\TileRepository;

class ReachabilityService
{
    private TileRepository $tileRepository;

    public function __construct(TileRepository $tileRepository)
    {
        $this->tileRepository = $tileRepository;
    }

    public function corridorVisibility(Tile $currentTile): array
    {
        if ($currentTile->getRoom() !== null) {
            return [];
        }

        $tiles = $this->tileRepository->findAll();
        foreach ($tiles as $tile) {
            $reachableTiles[$tile->getId()] = [
                'distance' => $this->tileDistance($currentTile, $tile),
                'angle' => $this->tileAngle($currentTile, $tile),
                'reachable' => true,
            ];
        }

        $maskingTiles = $this->getMaskingTilesInfos($currentTile);
        foreach ($maskingTiles as $maskingKey => $maskingTile) {
            foreach ($reachableTiles as $reachableTileKey => $reachableTile) {
                if (
                    $tile['angle'] > $maskingTile['startAngle'] &&
                    $reachableTile['angle'] < $maskingTile['endAngle'] &&
                    $reachableTile['distance'] > $reachableTiles[$maskingKey]['distance']
                ) {
                    $reachableTiles[$reachableTileKey] = false;
                }
            }
        }

        foreach ($reachableTiles as $reachableTile) {
            if ($reachableTile['visibility']) {
                $visibleTiles[] = $reachableTile;
            }
        }

        return $visibleTiles ?? [];
    }

    private function tileDistance(Tile $from, Tile $to)
    {
        $xDistance = ($from->getX() - $to->getX()) ** 2;
        $yDistance = ($from->getY() - $to->getY()) ** 2;

        return sqrt($xDistance + $yDistance);
    }

    private function tileAngle(Tile $from, Tile $to)
    {
        return $this->angle($from->getX(), $from->getY(), $to->getX(), $to->getY());
    }

    private function angle(int $fromX, int $fromY, int $toX, int $toY)
    {
        $xDistance = $fromX - $toX;
        $yDistance = $fromY - $toY;
        if ($xDistance === 0) {
            return 0;
        } else {
            return atan($yDistance / $xDistance);
        }
    }

    private function getMaskingTilesInfos(Tile $currentTile)
    {
        $fromX = $currentTile->getX() + 0.5;
        $fromY = $currentTile->getY() + 0.5;

        $maskingTiles = $this->tileRepository->findMaskingTiles();

        foreach ($maskingTiles as $tile) {
            [$angleStartX, $angleStartY, $angleEndX, $angleEndY] = $this->getMaskingAngles($currentTile, $tile);
            $maskingAngles[$tile->getId()] = [
                'startAngle' => $this->angle($fromX, $angleStartX, $fromY, $angleStartY),
                'endAngle' => $this->angle($fromX, $angleEndX, $fromY, $angleEndY),
            ];
        }

        return $maskingTiles;
    }

    private function getMaskingAngles(Tile $currentTile, Tile $maskingTile)
    {
        // M * 
        // * C
        if ($currentTile->getX() > $maskingTile->getX() && $currentTile->getY() > $maskingTile->getY()) {
            $coeffs = [0.5, -0.5, -0.5, 0.5];
        }
        // * C 
        // M *
        if ($currentTile->getX() > $maskingTile->getX() && $currentTile->getY() < $maskingTile->getY()) {
            $coeffs = [-0.5, -0.5, 0.5, 0.5];
        }
        // * M 
        // C *
        if ($currentTile->getX() < $maskingTile->getX() && $currentTile->getY() < $maskingTile->getY()) {
            $coeffs = [0.5, 0.5, -0.5, -0.5];
        }
        // C * 
        // * M
        if ($currentTile->getX() < $maskingTile->getX() && $currentTile->getY() > $maskingTile->getY()) {
            $coeffs = [-0.5, 0.5, 0.5, -0.5];
        }

        return [
            $maskingTile->getX() + $coeffs[0],
            $maskingTile->getY() + $coeffs[1],
            $maskingTile->getX() + $coeffs[2],
            $maskingTile->getY() + $coeffs[3],
        ];
    }
}
