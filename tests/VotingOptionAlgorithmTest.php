<?php

class VotingOptionAlgorithmTest extends PHPUnit_Framework_TestCase {

    public function testFibonacci() {
        $algo = new \VotingOptions\Algorithms\Fibonacci();
        $options = $algo->getOptions();

        // test that an array of options is generated
        if(!is_array($options)) {
            $this->fail();
        }

        $tempOptions = $options;

        // test that the array is built from instances of \VotingOptions\VotingOption
        $this->assertInstanceOf(
            "\\VotingOptions\\VotingOption",
            array_pop($tempOptions)
        );
        unset($tempOptions);

        // test that an option whit key "U" exists
        $this->assertTrue(array_key_exists('U', $options));

        $unknownOption = $options['U'];

        // test that the option with key "U" has the value of "unknown" (not case sensitive)
        $this->assertEquals(
            'unknown',
            strtolower($unknownOption->getValue())
        );
    }
}
