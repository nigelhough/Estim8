<?php
/**
 * Points Poker
 *
 * An Interactive Points Poker tool to help support Agile teams with story estimation
 *
 * PHP version 5
 *
 * @category Points Poker
 * @author   Nigel Hough <nigel@nigelhough.co.uk>
 * @author   Rien Sach <rien.sach@btinternet.com>
 */

/**
 * Points Poker Class
 *
 * @category Points Poker
 * @author   Nigel Hough <nigel@nigelhough.co.uk>
 * @link     TBC
 */
class pointsPoker {

    /**
     * Constructor
     *
     */
    function __construct() {

    }
    
    /**
     * Returns an integer of the current status. 
     *  0 = initial, no user story
     *  1 = user story, collect vote/s
     *  2 = user story and votes collected, allow user to select overall vote
     *  3 = user story, votes, and final vote - show info and reset button
     *
     */
    
    public function getStatus() {
        
    }
    
    /**
     * Returns the string of the current user story
     */
    
    public function getUserStory() {
        
    }
    
    /**
     * Processes the input array of all of its information
     * Inputs: $_POST and $_SESSION
     * 
     */
    
    public function processInput($post, $session) {
        
    }
    
    
    /**
     * Returns an array of all the current votes
     * 
     * 
     */
    
    public function getVotes() {
        
    }
    
    /**
     * Returns the overall story vote
     * 
     * 
     */
    
    public function getStoryPoints() {
        
    }

}