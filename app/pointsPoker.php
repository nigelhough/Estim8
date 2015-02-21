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
     * @var int (pointsPokerState)
     */
    private $state = pointsPokerState::INITIAL;

    /**
     * ID for the current Points Poker Session
     *
     * @var string
     */
    private $sessionID = null;

    /**
     * Story for Current Session
     *
     * @var string
     */
    private $story = null;

    /**
     * Result for Current Session
     *
     * @var int
     */
    private $result = null;

    /**
     * Array of all votes cast
     *
     * @var array
     */
    private $votes = null;
    
    /**
     * Constructor
     *
     */
    function __construct() {
        session_start();

        //Check for a Points Poker Session ID, could be used in future multi user games
        if(!(isset($_SESSION['POINTS_POKER'])
        && isset($_SESSION['POINTS_POKER']['SESSION_ID'])
        && $_SESSION['POINTS_POKER']['SESSION_ID'] != '')) {
            //If a Session ID doesn't exist, Create One
            $_SESSION['POINTS_POKER']['SESSION_ID'] = md5(uniqid());
        }
        $this->sessionID = $_SESSION['POINTS_POKER']['SESSION_ID'];

        //See if a story has already been logged against this session
        if(isset($_SESSION['POINTS_POKER'][$this->sessionID]['STORY'])
        && !isset($_SESSION['POINTS_POKER'][$this->sessionID]['DECISION'])) {
            //Set the class story and we are at least at voting stage
            $this->setStory($_SESSION['POINTS_POKER'][$this->sessionID]['STORY']);
            $this->setState(pointsPokerState::VOTING);
        }

        //Votes

        //If a Result has been submitted, we are at the results stage
       // if(isset($_SESSION['POINTS_POKER'][$this->sessionID]['RESULT'])) {
       //     $this->setResult($_SESSION['POINTS_POKER'][$this->sessionID]['RESULT']);
       //     $this->setState(pointsPokerState::RESULTS);
       // }
        
        
        if(isset($_SESSION['POINTS_POKER'][$this->sessionID]['DECISION'])) {
            $this->setState(pointsPokerState::RESULTS);
        }

    }

    /**
     * Processes the input array of all of its information
     * Inputs: $_POST and $_SESSION
     * 
     */
    public function processInput($userInput) {
        
        if(isset($userInput['reset'])) {
            
            // Reset the system
            $this->reset();
        }
        
        //If we are at initial state a story is passed
        if($this->state === pointsPokerState::INITIAL
        && isset($userInput['userStory'])
        && $userInput['userStory'] != '') {

            //Set User Story
            $this->setStory($userInput['userStory']);
            $this->setState(pointsPokerState::VOTING);
        }
        
        if($this->state === pointsPokerState::VOTING
        && isset($userInput['decision'])
        && $userInput['decision'] != '') {

            //Set status
            $this->setState(pointsPokerState::DECISION);
        }
        
        if($this->state === pointsPokerState::VOTING
        && isset($userInput['vote'])
        && $userInput['vote'] != '') {

            //Add vote
            $this->addVote($userInput['vote']);
        }
        
        if($this->state === pointsPokerState::DECISION
        && isset($userInput['vote'])
        && $userInput['vote'] != '') {

            //Add vote
            $this->setResult($userInput['vote']);
        }
        
        
        

        /*
        var_dump($this->state);
        var_dump($this->story);
        var_dump($this->result);

        var_dump($userInput);
        var_dump($persistentState);
        exit;
        */
    }

    
    
    public function reset() {
        session_destroy();
        session_start(); 
        session_regenerate_id(true); 
        $this->setState(pointsPokerState::INITIAL);
    }
    
    /**
     * Returns an array of all the current votes
     * 
     * 
     */
    public function getVotes() {
        $this->votes = $_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'];
        return $this->votes;
    }
    
    /**
     * Function to add a vote
     * 
     * 
     */
    public function addVote($vote) {
        
   
        
        if(!isset($_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'])) {
            $_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'] = array($vote);
            $this->votes = $_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'];
        } else {
            array_push($_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'],$vote);
            $this->votes = $_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'];
        }
        
    }
    
    /**
     * Returns an integer of the current State.
     *  0 = initial, no user story
     *  1 = user story, collect vote/s
     *  2 = user story and votes collected, allow user to select overall vote
     *  3 = user story, votes, and final vote - show info and reset button
     *
     * @return int (pointsPoketState)
     */
    public function getState() {
        return $this->state;
    }

    /*
     * Set the points poker state
     *
     * @var int pointsPokerState
     */
    private function setState($state) {
        $_SESSION['POINTS_POKER'][$this->sessionID]['DECISION'] = 1;
        $this->state = $state;
    }

    /**
     * Returns the string of the current user story
     *
     * return string
     */
    public function getUserStory() {
        return $this->story;
    }

    /*
     * Set the points poker story
     *
     * @var string
     */
    private function setStory($story) {
        $_SESSION['POINTS_POKER'][$this->sessionID]['STORY'] = $story;
        $this->story = $story;
    }

    /*
     * Set the points poker result
     *
     * @var int
     */
    private function setResult($result) {
        $_SESSION['POINTS_POKER'][$this->sessionID]['RESULT'] = $result;
        $this->result = intval($result);
    }

    /**
     * Returns the overall story vote
     *
     * return int
     */
    public function getResult() {
        return $this->result;
    }

}