<?php

namespace App\DataFixtures;

use App\Entity\Tile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TileFixtures extends Fixture implements DependentFixtureInterface
{
    public const TILES = [
        'passage' => [
            ['coords' => '0,0', 'borders' => ['wall', null, null, 'wall'], 'occupant' => 'dwarf'],
            ['coords' => '1,0', 'borders' => ['wall', null, null, 'wall']],
            ['coords' => '2,0', 'borders' => ['wall', null, null, 'wall']],
        ],
        'green' => [
            ['coords' => '1,1', 'borders' => ['wall', null, null, 'wall']],
            ['coords' => '1,2', 'borders' => ['door', null, null, null]],
            ['coords' => '1,3', 'borders' => ['wall', 'wall', null, null]],
            ['coords' => '2,1', 'borders' => [null, null, 'open-door', 'wall']],
            ['coords' => '2,2', 'borders' => [null, null, 'wall', null]],
            ['coords' => '2,3', 'borders' => [null, 'wall', 'wall', null]],
        ],
        'red' => [
            ['coords' => '4,1', 'borders' => ['wall', null, null, 'wall']],
            ['coords' => '4,2', 'borders' => ['wall', null, null, null]],
            ['coords' => '4,3', 'borders' => ['wall', 'wall', null, null]],
            ['coords' => '5,1', 'borders' => [null, null, 'wall', 'wall']],
            ['coords' => '5,2', 'borders' => [null, null, 'wall', null]],
            ['coords' => '5,3', 'borders' => [null, 'wall', 'wall', null]],
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::TILES as $room => $tiles) {
            foreach ($tiles as $tileData) {
                [$x, $y] = explode(',', $tileData['coords']);
                [$north, $east, $south, $west] = $tileData['borders'];
        
                $tile = new Tile();

                $tile->setX($x);
                $tile->setY($y);
                $tile->setNorth($north);
                $tile->setEast($east);
                $tile->setSouth($south);
                $tile->setWest($west);

                if(key_exists('occupant', $tileData)) {
                    $tile->setOccupant($this->getReference($tileData['occupant']));
                }
                $tile->setRoom($this->getReference($room));

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
