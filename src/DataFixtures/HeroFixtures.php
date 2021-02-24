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
        $hero->setX(1);
        $hero->setY(1);
        $this->addReference('dwarf', $hero);
        $manager->persist($hero);

        $manager->flush();
    }
}
