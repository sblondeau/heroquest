<?php

namespace App\DataFixtures;

use App\Entity\Tile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TileFixtures extends Fixture implements DependentFixtureInterface
{
    const WALL = 'wall';
    const DOOR = 'door';
    const OPEN_DOOR = 'open-door';

    private function makeRoom(int $startX, int $endX, int $startY, int $endY): array
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

                $room[$x][$y] = ['borders' => [$wallNorth, $wallEast, $wallSouth, $wallWest]];
            }
        }

        return $room;
    }



    public function load(ObjectManager $manager)
    {
        $rooms['red'] = $this->makeRoom(1, 4, 1, 6);
        $rooms['green'] = $this->makeRoom(6, 9, 1, 5);

        $rooms['passage'][0][0] = [];
        $rooms['passage'][0][1] = [];
        $rooms['passage'][1][0] = [];

        foreach ($rooms as $room => $tileXData) {
            foreach ($tileXData as $x => $tileYData) {
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

                    $tile->setRoom($this->getReference($room));

                    $manager->persist($tile);
                }
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
