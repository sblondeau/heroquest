<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Hero;

class HeroFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $hero = new Hero;
        $hero->setName('Dwarf');
        $hero->setX(0);
        $hero->setY(0);
        $manager->persist($hero);

        $manager->flush();
    }
}
