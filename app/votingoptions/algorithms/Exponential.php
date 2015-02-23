<?php

namespace VotingOptions\Algorithms;

class Exponential extends Algorithm {

    protected function generateOptions($requestedOptions) {
        $this->exponential(2, $requestedOptions);
    }

    /**
     * Exponential algo to generate VotingOptions
     *
     * @param $n
     * @return number
     */
    function exponential($base, $n) {
        for ($i = 0; $i < $n; $i++) {
            $result = pow($base, $i);

            $this->addOption($result, $result);
        }

        return $result;
    }
}