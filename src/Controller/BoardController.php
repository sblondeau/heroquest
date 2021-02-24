<?php

namespace App\Controller;

use App\Entity\Tile;
use App\Repository\TileRepository;
use App\Repository\HeroRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoardController extends AbstractController
{
    public const BOARD_COLUMNS = 20;
    public const BOARD_ROWS = 30;
    public const DIRECTIONS = ['N' => [0, -1], 'E' => [1, 0], 'S' => [0, 1], 'W' => [-1, 0]];

    /**
     * @Route("/", name="board")
     */
    public function index(TileRepository $tileRepository, HeroRepository $heroRepository): Response
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
            'heroes' => $heroRepository->findAll(),
            'cols' => self::BOARD_COLUMNS,
            'rows' => self::BOARD_ROWS,
        ]);
    }

    /**
     * @Route("/move/{direction<N|S|E|W>}", name="move")
     */
    public function move(HeroRepository $heroRepository, TileRepository $tileRepository, EntityManagerInterface $entityManager, string $direction)
    {
        $occupant = $heroRepository->findOneBy([]);
        $tile = $tileRepository->findOneByOccupant($occupant);

        [$xModifier, $yModifier] = self::DIRECTIONS[$direction];

        $destinationTile = $tileRepository->findOneBy(['x' => $tile->getX() + $xModifier, 'y' => $tile->getY() + $yModifier]);

        if ($destinationTile instanceof Tile) {
            $destinationTile->setOccupant($tile->getOccupant());
            $tile->setOccupant(null);
            $entityManager->persist($tile);
            $entityManager->flush();
        } else {
            $this->addFlash('danger', 'impossible move');
        }

        return $this->redirectToRoute('board');
    }
}
