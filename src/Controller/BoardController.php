<?php

namespace App\Controller;

use App\Repository\FurnitureRepository;
use App\Repository\TileRepository;
use App\Service\FurnitureOrganizer;
use App\Service\MoveService;
use App\Service\TurnService;
use App\Service\VisibilityService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use RuntimeException;
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
        TurnService $turnService,
        FurnitureOrganizer $furnitureOrganizer
    ): Response {
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
            'currentCharacter' => $turnService->currentCharacter(),
        ]);
    }

    /**
     * @Route("/end-of-turn", name="end_turn")
     */
    public function endOfTurn(TurnService $turnService, EntityManagerInterface $entityManager, VisibilityService $visibilityService)
    {
        $currentCharacter = $turnService->currentCharacter();
        $currentCharacter->setHasPlayedThisTurn(1)->setRemainingMove(0);
        $entityManager->persist($currentCharacter);
        $entityManager->flush();

        $visibilityService->changeVisibility($currentCharacter->getTile());

        return $this->redirectToRoute('board');
    }

    /**
     * @Route("/move/{direction<North|South|East|West>}", name="move")
     */
    public function move(MoveService $moveService, TurnService $turnService, string $direction, VisibilityService $visibilityService)
    {
        $currentCharacter = $turnService->currentCharacter();

        try {
            $moveService->move($currentCharacter, $direction);
            $visibilityService->changeVisibility($currentCharacter->getTile());
        } catch (RuntimeException $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }

        return $this->redirectToRoute('board');
    }
}
