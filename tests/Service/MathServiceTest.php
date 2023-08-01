<?php
namespace App\Tests\Service;

use App\Service\MathService;
use PHPUnit\Framework\TestCase;

class MathServiceTest extends TestCase
{
    public function testAngle()
    {
        $this->assertEquals(M_PI_4, MathService::angle(2,2,3,1));
        $this->assertEquals(M_PI_2, MathService::angle(2,2,2,1));
        $this->assertEquals(0, MathService::angle(2,2,3,2));
        $this->assertEquals(M_PI, MathService::angle(2,2,1,2));
        $this->assertEquals(3*M_PI_4, MathService::angle(2,2,1,1));
        $this->assertEquals(5*M_PI_4, MathService::angle(2,2,1,3));
        $this->assertEquals(3*M_PI_2, MathService::angle(2,2,2,3));
        $this->assertEquals(7*M_PI_4, MathService::angle(2,2,3,3));
    }

    public function testAngleFarCoords()
    {
        $this->assertEquals(M_PI, MathService::angle(3,3,1,3));
        $this->assertEquals(0, MathService::angle(3,3,5,3));
    }
}