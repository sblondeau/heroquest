<?php

namespace App\Controller;

use App\Entity\Tile;
use App\Repository\FurnitureRepository;
use App\Repository\TileRepository;
use App\Repository\HeroRepository;
use App\Service\FurnitureOrganizer;
use App\Service\MoveService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoardController extends AbstractController
{
    public const BOARD_COLUMNS = 10;
    public const BOARD_ROWS = 20;

    /**
     * @Route("/", name="board")
     */
    public function index(
        TileRepository $tileRepository,
        FurnitureRepository $furnitureRepository,
        HeroRepository $heroRepository,
        FurnitureOrganizer $furnitureOrganizer
        ): Response  {
        $tiles = $tileRepository->findAll();
        foreach ($tiles as $tile) {
            $tileCoords[$tile->getY()][$tile->getX()] = $tile;
            if ($tile->getOccupant() !== null) {
                $heroes[] = $tile->getOccupant();
            }
        }

        for ($y = 0; $y < self::BOARD_ROWS; $y++) {
            for ($x = 0; $x < self::BOARD_COLUMNS; $x++) {
                $boardTiles[] = $tileCoords[$y][$x] ?? null;
            }
        }

        foreach ($furnitureRepository->findAll() as $furniture) {
            [$x, $y] = $furnitureOrganizer->getStartPoint($furniture);
            $furnitures[] = [
                'data' => $furniture, 
                'startPoint' => [$x, $y]
            ];
        }


        return $this->render('board/index.html.twig', [
            'boardTiles' => $boardTiles,
            'furnitures' => $furnitures,
            'heroes' => $heroes ?? [],
        ]);
    }

    /**
     * @Route("/move/{direction<North|South|East|West>}", name="move")
     */
    public function move(HeroRepository $heroRepository, MoveService $moveService, string $direction)
    {
        $occupant = $heroRepository->findOneBy([]);
        try {
            $moveService->move($occupant, $direction);
        } catch (Exception $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }

        return $this->redirectToRoute('board');
    }
}
