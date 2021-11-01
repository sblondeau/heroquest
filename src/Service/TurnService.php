<?php

namespace App\Service;

use App\Entity\Character;
use App\Repository\HeroRepository;

class TurnService
{
    private HeroRepository $heroRepository;

    public function __construct(HeroRepository $heroRepository)
    {
        $this->heroRepository = $heroRepository;
    }

    public function currentHero()
    {
        $this->checkIfEndOfTurn();
        return $this->heroRepository->findOneBy(['hasPlayedThisTurn' => 0]);
    }

    private function checkIfEndOfTurn()
    {
        if(!$this->heroRepository->findOneBy(['hasPlayedThisTurn' => 0]) instanceof Character)
        {
            $this->heroRepository->resetTurn();
        }
    }
}
