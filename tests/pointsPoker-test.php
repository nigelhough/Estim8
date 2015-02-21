<?php

include_once '../app/pointsPoker.php';

class PointsPokerTest extends PHPUnit_Framework_TestCase
{
    public function testInitalise()
    {
        $pointsPoker = new pointsPoker();

        $this->assertInstanceOf('pointsPoker', $pointsPoker);

    }
}
