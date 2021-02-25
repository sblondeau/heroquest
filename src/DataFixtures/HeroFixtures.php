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
        $this->addReference('dwarf', $dwarf);
        $manager->persist($dwarf);
        
        $barbarian = new Hero;
        $barbarian->setName('Barbarian');
        $this->addReference('barbarian', $barbarian);
        $manager->persist($barbarian);

        $manager->flush();
    }
}
