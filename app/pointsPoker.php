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
    private $votes = array();
    
    /*
     * The algorithm that generates the voting options
     *
     * @var \VotingOptions\Algorithms\Algorithm
     */
    private $votingOptionsGeneratorAlgorithm;
    
    /**
     * Constructor
     *
     * @param \VotingOptions\Algorithms\Algorithm $algorithm
     */
    function __construct(\VotingOptions\Algorithms\Algorithm $algorithm) {
        session_start();

        $this->votingOptionsGeneratorAlgorithm = $algorithm;

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
        && $_SESSION['POINTS_POKER'][$this->sessionID]['STORY'] != '') {
            //Set the class story and we are at least at voting stage
            $this->setStory($_SESSION['POINTS_POKER'][$this->sessionID]['STORY']);
            $this->setState(pointsPokerState::VOTING);
        }

        //Load Votes from Session
        if(isset($_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'])
        && is_array($_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'])
        && count($_SESSION['POINTS_POKER'][$this->sessionID]['VOTES']) > 0) {
            $this->votes = $_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'];
        }

        //If a Result has been submitted, we are at the results stage
        if(isset($_SESSION['POINTS_POKER'][$this->sessionID]['RESULT'])
        && $_SESSION['POINTS_POKER'][$this->sessionID]['RESULT'] != null) {
            $this->setResult($_SESSION['POINTS_POKER'][$this->sessionID]['RESULT']);
            $this->setState(pointsPokerState::RESULT);
        }

        //If a Decsion is set we are at results stage
        if(isset($_SESSION['POINTS_POKER'][$this->sessionID]['DECISION'])
        && $_SESSION['POINTS_POKER'][$this->sessionID]['DECISION'] != null) {
            $this->setState(pointsPokerState::RESULT);
        }

    }

    /**
     * Processes the input array of all of its information
     * Inputs: $_POST and $_SESSION
     * 
     */
    public function processInput($userInput) {

        //Reset the Session
        if(isset($userInput['reset']) && $userInput['reset']==$this->sessionID) {
            // Reset the system
            $this->reset();
        }

        //If we are at initial state and a story is passed
        if($this->state === pointsPokerState::INITIAL
        && isset($userInput['userStory'])
        && $userInput['userStory'] != '') {

            //Set User Story
            $this->setStory($userInput['userStory']);
            $this->setState(pointsPokerState::VOTING);
        }

        //If we are at voting state and a vote is passed
        if($this->state === pointsPokerState::VOTING
        && isset($userInput['vote'])
        && $userInput['vote'] != '') {

            //Add vote
            $this->addVote($userInput['vote']);
        }

        //If we are at voting state and a final decision is passed
        if($this->state === pointsPokerState::VOTING
        && isset($userInput['end_voting'])
        && $userInput['end_voting']
        && isset($_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'])) {
            //Set to make decsion mode
            $this->setState(pointsPokerState::DECISION);
        }

        //If a final decision is passed
        if(isset($userInput['decision']) && $userInput['decision'] != '') {

            //Set the result
            $this->setResult($userInput['decision']);
            $this->setState(pointsPokerState::RESULT);
        }
    }

    /**
     * Reset Session
     *
     *
     */
    public function reset() {
        //Reset Class Variables
        $this->setSessionID(null);
        $this->story = null;
        $this->result = null;
        $this->votes = array();
        $this->setState(pointsPokerState::INITIAL);
    }

    /**
     * Set SessionID
     *
     *
     */
    public function setSessionID($sessionID) {
        $_SESSION['POINTS_POKER']['SESSION_ID'] = $sessionID;
        $this->sessionID = $sessionID;
    }

    /**
     * Get SessionID
     *
     * @return string
     */
    public function getSessionID() {
        return $this->sessionID;
    }

    /**
     * Returns an array of all the current votes
     * 
     * @return array
     */
    public function getVotes() {
        return $this->votes;
    }

    /**
     * Returns an array of all the current votes
     *
     *
     */
    public function getVotesCount() {
        return count($this->votes);
    }
    
    /**
     * Function to add a vote
     * 
     * 
     */
    public function addVote($vote) {
        if(!isset($_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'])) {
            $_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'] = array();
        }
        $_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'][] = $vote;
        $this->votes = $_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'];
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
        $this->state = $state;
    }

    /**
     * Returns the string of the current user story
     *
     * @return string
     */
    public function getUserStory() {
        return $this->story;
    }
    
    
    /**
     * Returns the array of possible voting options
     *
     * @return array
     */
    public function getVotingOptions() {
        return $this->votingOptionsGeneratorAlgorithm->getOptions();
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
     * @return int
     */
    public function getResult() {
        return $this->result;
    }

}