<?php

class VotingOptionTest extends PHPUnit_Framework_TestCase {

    public function testGetters() {
        $option = new \VotingOptions\VotingOption("key", "value");

        $this->assertEquals("key", $option->getKey());
        $this->assertEquals("value", $option->getValue());
    }
}
