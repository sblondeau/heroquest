<?php

namespace App\DataFixtures;

use App\Entity\Tile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TileFixtures extends Fixture
{
    public const TILES = [
        'green' => ['1,1', '1,2', '1,3', '2,1', '2,2', '2,3'],
        'red' => ['4,1', '4,2', '4,3', '5,1', '5,2', '5,3'],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::TILES as $room => $tileData) {
            foreach ($tileData as $tileCoords) {
                [$x, $y] = explode(',', $tileCoords);
                $tile = new Tile();
                $tile->setX($x);
                $tile->setY($y);
                $tile->setRoom($this->getReference($room));

                $manager->persist($tile);
            }
        }


        $manager->flush();
    }
}
