<?php

namespace App\Tests\Service;

use App\Entity\Tile;
use App\Service\ReachabilityService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ReachabilityServiceTest extends KernelTestCase
{
    private ReachabilityService $reachabilityService;
    private Tile $tileFrom;
    private Tile $tileW;
    private Tile $tileN;
    private Tile $tileS;
    private Tile $tileE;

    public function setUp(): void
    {
        self::bootKernel();
        $this->reachabilityService = self::$container->get(ReachabilityService::class);
        
        /**
         * . . . A . .
         * . . B N . .
         * . . . . . .
         * . W . X . E
         * . . . . . .
         * . . . S . .
         */
        $this->tileFrom = (new Tile())->setX(3)->setY(3);
        // O
        $this->tileN = (new Tile())->setX(3)->setY(1);
        $this->tileW = (new Tile())->setX(1)->setY(3);
        $this->tileS = (new Tile())->setX(3)->setY(5);
        $this->tileE = (new Tile())->setX(5)->setY(3);
        // #
        $this->tileA = (new Tile())->setX(3)->setY(0);
        $this->tileB = (new Tile())->setX(2)->setY(1);
   }

    public function testTileDistance(): void
    {
        $this->assertEquals(2, $this->reachabilityService->tileDistance($this->tileFrom, $this->tileN));
        $this->assertEquals(2, $this->reachabilityService->tileDistance($this->tileFrom, $this->tileW));
        $this->assertEquals(2, $this->reachabilityService->tileDistance($this->tileFrom, $this->tileS));
        $this->assertEquals(2, $this->reachabilityService->tileDistance($this->tileFrom, $this->tileE));
    }

    public function testTileAngles(): void
    {
        $this->assertEquals(M_PI_2, $this->reachabilityService->tileAngle($this->tileFrom, $this->tileN));
        $this->assertEquals(M_PI, $this->reachabilityService->tileAngle($this->tileFrom, $this->tileW));
        $this->assertEquals(3 * M_PI_2, $this->reachabilityService->tileAngle($this->tileFrom, $this->tileS));
        $this->assertEquals(0, $this->reachabilityService->tileAngle($this->tileFrom, $this->tileE));
    }

    // public function testTileReachable(): void
    // {
    //     $this->assertTrue($this->reachabilityService->isReachable($this->tileN));
    // }

}
