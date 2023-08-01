<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Furniture;

class FurnitureFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $furniture = new Furniture();
        $furniture->setName('table');
        $furniture->setWidth(3);
        $furniture->setHeight(2);
        $furniture->setDirection('South');
        $this->addReference('table', $furniture);
        $manager->persist($furniture);

        $manager->flush();
    }
}
