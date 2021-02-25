<?php

namespace App\DataFixtures;

use App\Controller\BoardController;
use App\Entity\Tile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TileFixtures extends Fixture implements DependentFixtureInterface
{
    const WALL = 'wall';
    const DOOR = 'door';
    const OPEN_DOOR = 'open-door';

    private array $board = [];

    private function makeRoom(string $roomColor, int $startX, int $endX, int $startY, int $endY): void
    {
        for ($x = $startX; $x < $endX; $x++) {
            for ($y = $startY; $y < $endY; $y++) {
                $wallNorth = $wallEast = $wallSouth = $wallWest = null;
                if ($x == $startX) {
                    $wallWest = self::WALL;
                }
                if ($x == $endX - 1) {
                    $wallEast = self::WALL;
                }
                if ($y == $startY) {
                    $wallNorth = self::WALL;
                }
                if ($y == $endY - 1) {
                    $wallSouth = self::WALL;
                }

                $this->board[$x][$y] = [
                    'room' => $roomColor,
                    'borders' => [$wallNorth, $wallEast, $wallSouth, $wallWest],
                ];
            }
        }
    }

    public function load(ObjectManager $manager)
    {
        $this->makeRoom('red', 1, 4, 1, 6);
        $this->makeRoom('green', 5, 9, 1, 5);

        for ($y = 0; $y < BoardController::BOARD_ROWS; $y++) {
            for ($x = 0; $x < BoardController::BOARD_COLUMNS; $x++) {
                $this->board[$x][$y] ??= [];
            }
        }
        
        $this->board[3][2]['borders'][1] = self::OPEN_DOOR;
        $this->board[5][3]['borders'][3] = self::OPEN_DOOR;
        $this->board[2][3]['occupant'] = 'dwarf';
        $this->board[2][4]['occupant'] = 'barbarian';

        foreach ($this->board as $x => $tileYData) {
            foreach ($tileYData as $y => $tileData) {
                $tile = new Tile();

                $tile->setX($x);
                $tile->setY($y);

                if (key_exists('borders', $tileData)) {
                    [$north, $east, $south, $west] = $tileData['borders'];
                    $tile->setNorth($north);
                    $tile->setEast($east);
                    $tile->setSouth($south);
                    $tile->setWest($west);
                }

                if (key_exists('occupant', $tileData)) {
                    $tile->setOccupant($this->getReference($tileData['occupant']));
                }

                $tile->setRoom($this->getReference($tileData['room'] ?? 'passage'));

                $manager->persist($tile);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            RoomFixtures::class,
            HeroFixtures::class,
        ];
    }
}
