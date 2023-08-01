<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Hero;

class HeroFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $dwarf = new Hero;
        $dwarf->setName('Dwarf');
        $dwarf->setHasPlayedThisTurn(true);
        $dwarf->setMoveDiceNumber(3);
        $this->addReference('dwarf', $dwarf);
        $manager->persist($dwarf);
        
        $barbarian = new Hero;
        $barbarian->setName('Barbarian');
        $barbarian->setHasPlayedThisTurn(false);
        $barbarian->setMoveDiceNumber(2);
        $this->addReference('barbarian', $barbarian);
        $manager->persist($barbarian);

        $manager->flush();
    }
}
