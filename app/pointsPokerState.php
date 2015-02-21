<?php
/**
 * Points Poker
 *
 * Possible States a Points Poker Session could be in
 *
 * PHP version 5
 *
 * @category Points Poker
 * @author   Nigel Hough <nigel@nigelhough.co.uk>
 * @author   Rien Sach <rien.sach@btinternet.com>
 */

interface pointsPokerState {
    const INITIAL	= 0;
    const VOTING 	= 1;
    const DECISION 	= 2;
    const RESULTS	= 3;
}
