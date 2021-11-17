<?php

namespace App\Service;

use App\Entity\Character;
use App\Repository\HeroRepository;
use Doctrine\ORM\EntityManagerInterface;

class TurnService
{
    const DICE_FACES = 6;

    private HeroRepository $heroRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(HeroRepository $heroRepository, EntityManagerInterface $entityManager)
    {
        $this->heroRepository = $heroRepository;
        $this->entityManager = $entityManager;
    }

    public function currentCharacter()
    {
        $this->checkIfEndOfTurn();
        $currentCharacter = $this->heroRepository->findOneBy(['hasPlayedThisTurn' => 0]);

        return $currentCharacter;
    }

    private function checkIfEndOfTurn()
    {
        if (!$this->heroRepository->findOneBy(['hasPlayedThisTurn' => 0]) instanceof Character) {
            $characters = $this->heroRepository->findAll();
            foreach($characters as $character) {
                $character->setHasPlayedThisTurn(0);
                $character->setRemainingMove($this->launchDice($character->getMoveDiceNumber()));
                $this->entityManager->persist($character);
            }

            $this->entityManager->flush();
        }
    }

    private function launchDice(int $numberOfDices = 2)
    {
        $diceResult = 0;
        for ($i = 0; $i < $numberOfDices; $i++) {
            $diceResult += rand(1, 6);
        }

        return $diceResult;
    }
    
}
