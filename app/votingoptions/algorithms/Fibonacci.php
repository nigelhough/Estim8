<?php

namespace VotingOptions\Algorithms;

class Fibonacci extends Algorithm {

    protected function generateOptions($requestedOptions) {
        // Generate VotingOptions using the Fibonacci algo.
        $this->fibonacciIterative($requestedOptions);
    }

    /**
     * Generate VotingOptions using the iterative Fibonacci algorithm
     * Note: the recursive algorithm would be a lot slower, hence the decision to use the iterative
     *
     * @param int $n
     * @return int
     */
    function fibonacciIterative($n) {
        if ($n == 0 || $n == 1) {
            $this->addOption($n, $n);

            return $n;
        }

        $prevPrev = 0;
        $prev = 1;
        $result = 0;

        for ($i = 2; $i <= $n + 1; $i++) {
            $result = $prev + $prevPrev;
            $prevPrev = $prev;
            $prev = $result;

            $this->addOption($result, $result);
        }

        return $result;
    }
}