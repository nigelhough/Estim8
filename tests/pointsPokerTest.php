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
        $this->assertClassHasAttribute('sessionID', 'pointsPoker');
        $this->assertClassHasAttribute('story', 'pointsPoker');
        $this->assertClassHasAttribute('state', 'pointsPoker');
        $this->assertClassHasAttribute('result', 'pointsPoker');
        $this->assertClassHasAttribute('votes', 'pointsPoker');
        $this->assertClassHasAttribute('votingOptionsGeneratorAlgorithm', 'pointsPoker');

    }
}
