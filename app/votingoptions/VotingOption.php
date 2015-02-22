<?php

namespace VotingOptions;

/**
 * Class VotingOption
 */
class VotingOption {
    private $key, $value;

    public function __construct($key, $value) {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }
}