<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoomFixtures extends Fixture
{
    public const ROOMS = [
        'yellow', 'red', 'blue', 'green',
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::ROOMS as $roomData) {
            $room = new Room();
            $room->setColor($roomData);
            $room->setIsVisited(false);
            $this->addReference($roomData, $room);
            $manager->persist($room);
        }

        $manager->flush();
    }
}
