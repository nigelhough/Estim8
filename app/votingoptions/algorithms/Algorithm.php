<?php

namespace VotingOptions\Algorithms;

abstract class Algorithm {

    /**
     * @var array - array of \VotingOptions\VotingOption
     */
    protected $options = array();

    protected $optionsGenerated = false;
    protected $requestedOptions;

    public function __construct($requestedOptions = 11) {
        $this->requestedOptions = $requestedOptions;
    }

    protected abstract function generateOptions($requestedOptions);

    /**
     * Return available voting options
     *
     * @return array
     */
    public function getOptions(){
        if(!$this->optionsGenerated) {
            // generate options
            $this->generateOptions($this->requestedOptions - 1);

            // add the "unknown" option
            $this->addOption("U", "Unknown");

            $this->optionsGenerated = true;
        }
        return $this->options;
    }

    /**
     * Put a new \VotingOptions\VotingOption into the $options array
     * @param $key
     * @param $value
     */
    protected function addOption($key, $value) {
        $this->options[$key] = new \VotingOptions\VotingOption($key, $value);
    }
}