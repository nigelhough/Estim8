<?php

class ExponentialVotingOptionAlgorithmTest extends PHPUnit_Framework_TestCase {

    public function testConstructor()
    {
        $algo = new \VotingOptions\Algorithms\Exponential(4);
        $options = $algo->getOptions();

        // test that an array of options is generated
        $this->assertTrue(is_array($options));

        // test that the number of generated options matches the requested number
        $this->assertEquals(4, sizeof($options));
    }

    public function testOptions() {
        $algo = new \VotingOptions\Algorithms\Exponential();
        $options = $algo->getOptions();

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
