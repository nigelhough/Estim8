<?php

include_once '../app/pointsPoker.php';

class PointsPokerTest extends PHPUnit_Framework_TestCase
{
    public function testInitalise()
    {
        $pointsPoker = new pointsPoker();

        $this->assertInstanceOf('pointsPoker', $pointsPoker);

    }
    
    public function testAttributes()
    {
        $this->assertClassHasAttribute('sessionID', 'String');
        $this->assertClassHasAttribute('story', 'String');
        $this->assertClassHasAttribute('state', 'Int');
        $this->assertClassHasAttribute('result', 'Int');
        $this->assertClassHasAttribute('votes', 'Array');
        $this->assertClassHasAttribute('votingOptions', 'Array');

    }
}
