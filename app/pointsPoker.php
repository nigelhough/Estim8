<?php
require_once SITE_ROOT.'app/pointsPokerState.php';

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
     * Current State of a Points Poker Session
     *
     * @var pointsPokerState
     */
    private $state = pointsPokerState::INITIAL;

    /**
     * Story for Current Session
     *
     * @var pointsPokerState
     */
    private $story = null;

    /**
     * Constructor
     *
     */
    function __construct() {
        session_start();

        //Check for a Points Poker Session ID, could be used in future multi user games
        if(isset($_SESSION['POINTS_POKER'])
            && isset($_SESSION['POINTS_POKER']['SESSION_ID'])
            && $_SESSION['POINTS_POKER']['SESSION_ID'] != '') {

        } else {
            $_SESSION['POINTS_POKER']['SESSION_ID'] = md5(uniqid());
        }

        //See if a story has already been logged against this session
        if(isset($_SESSION['POINTS_POKER']['SESSION_ID']['STORY'])) {
            //Set the class story and we are at least at voting stage
            $this->story = $_SESSION['POINTS_POKER']['SESSION_ID']['STORY'];
            $this->state = pointsPokerState::VOTING;
        }



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
    
    public function processInput($userInput, $persistnatState) {
        var_dump($userInput);
        var_dump($persistnatState);
        exit;
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