<?php

namespace App\Controller;

use App\Repository\TileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use index;

class BoardController extends AbstractController
{
    public const BOARD_COLUMNS = 20;
    public const BOARD_ROWS = 30;

    /**
     * @Route("/", name="board")
     */
    public function index(TileRepository $tileRepository): Response
    {
        $tiles = $tileRepository->findAll();
        foreach ($tiles as $tile) {
            $tileCoords[$tile->getX()][$tile->getY()] = $tile;
        }

        for ($x = 0; $x < self::BOARD_COLUMNS; $x++) {
            for ($y = 0; $y < self::BOARD_ROWS; $y++) {
                $boardTiles[] = $tileCoords[$x][$y] ?? null;
            }
        }

        return $this->render('board/index.html.twig', [
            'boardTiles' => $boardTiles,
            'cols' => self::BOARD_COLUMNS,
            'rows' => self::BOARD_ROWS,
        ]);
    }
}
