<?php

namespace estim8\storyEstimation;

/**
 * Story Estimation State
 *
 * Possible States of Story Estimation
 *
 * PHP version 5
 *
 * @category Points Poker
 * @author   Nigel Hough <nigel@nigelhough.co.uk>
 * @author   Rien Sach <rien.sach@btinternet.com>
 */

interface storyEstimationState {
    const INITIAL	    = 0;
    const VOTING 	    = 1;
    const ESTIMATE   	= 2;
    const RESULT	    = 3;
}
