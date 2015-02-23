<?php

namespace PointsPoker\storyEstimation;

require_once SITE_ROOT.'app/storyEstimationState.php';
require_once SITE_ROOT.'app/story.php';

/**
 * Story Estimation
 *
 * The Estimation of a Single Story
 *
 * PHP version 5
 *
 * @category Points Poker
 * @author   Nigel Hough <nigel@nigelhough.co.uk>
 * @author   Rien Sach <rien.sach@btinternet.com>
 */

/**
 * Story Estimation
 *
 * @category Points Poker
 * @author   Nigel Hough <nigel@nigelhough.co.uk>
 * @link     TBC
 */
class storyEstimation {

    /**
     * Current State
     *
     * @var int (pointsPokerState)
     */
    private $state = storyEstimationState::INITIAL;

    /**
     * ID for the current Story Estimation
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
     * Estimate, the final agreed estimate from the story estimation
     *
     * @var int
     */
    private $estimate = null;

    /**
     * Array of all votes cast
     *
     * @var array
     */
    private $votingRounds = array(array());
    
    /*
     * Set the points poker result
     *
     * @var array
     */
    private $votingOptions = array(0 => 0, 1 => 1, 2 => 2, 3 => 3, 5 => 5, 8 => 8, 13 => 13, 20 => 20, 40 => 40, 100 => 100, 'U' => 'Unknown');
    
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
        && $_SESSION['POINTS_POKER'][$this->sessionID]['STORY'] != '') {
            //Set the class story and we are at least at voting stage
            $this->setStory($_SESSION['POINTS_POKER'][$this->sessionID]['STORY']);
            $this->setState(storyEstimationState::VOTING);
        }

        //Load Votes from Session
        if(isset($_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'])
        && is_array($_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'])
        && count($_SESSION['POINTS_POKER'][$this->sessionID]['VOTES']) > 0) {
            $this->votingRounds = $_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'];
        }

        //If a Result has been submitted, we are at the results stage
        if(isset($_SESSION['POINTS_POKER'][$this->sessionID]['RESULT'])
        && $_SESSION['POINTS_POKER'][$this->sessionID]['RESULT'] != null) {
            $this->setEstimate($_SESSION['POINTS_POKER'][$this->sessionID]['RESULT']);
            $this->setState(storyEstimationState::RESULT);
        }

        //If an Estimate is set we are at results stage
        if(isset($_SESSION['POINTS_POKER'][$this->sessionID]['ESTIMATE'])
        && $_SESSION['POINTS_POKER'][$this->sessionID]['ESTIMATE'] != null) {
            $this->setState(storyEstimationState::RESULT);
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
        if($this->state === storyEstimationState::INITIAL
        && isset($userInput['userStory'])
        && $userInput['userStory'] != '') {

            //Set User Story
            $this->setStory($userInput['userStory']);
            $this->setState(storyEstimationState::VOTING);
        }

        //If we are at voting state and a vote is passed
        if($this->state === storyEstimationState::VOTING
        && isset($userInput['vote'])
        && $userInput['vote'] != '') {

            //Add vote
            $this->addVote($userInput['vote']);
        }

        //If we are at voting state and a final estimate is passed
        if($this->state === storyEstimationState::VOTING
        && isset($userInput['end_voting'])
        && $userInput['end_voting']
        && isset($_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'][0])) {
            //Set to make estimate mode
            $this->setState(storyEstimationState::ESTIMATE);
        }

        //If a final estimate is passed
        if(isset($userInput['estimate']) && $userInput['estimate'] != '') {
            //Set the result
            $this->setEstimate($userInput['estimate']);
            $this->setState(storyEstimationState::RESULT);
        }

        //If a re-vote is passed,
        if(isset($userInput['re-vote']) && $userInput['re-vote'] != '') {
            array_unshift($_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'],array());
            $this->votes = $_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'];
            $this->setState(storyEstimationState::VOTING);
        }
    }

    /**
     * Reset Session
     *
     */
    public function reset() {
        //Reset Class Variables
        $this->setSessionID(null);
        unset($this->story);
        $this->result = null;
        $this->votingRounds = array(array());
        $this->setState(storyEstimationState::INITIAL);
    }

    /**
     * Set SessionID
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
     * Returns count of voting rounds array
     *
     * @return int
     */
    public function getNoVotingRounds() {
        return count($this->votingRounds);
    }

    /**
     * Returns current voting round
     *
     * @return string
     */
    public function getVotingRound() {
        $noRounds = $this->getNoVotingRounds();
        $trailing = $this->getSuffics($noRounds);

        return $noRounds.$trailing;
    }

    /**
     * Returns an array of all voting rounds, which contain an array of votes
     *
     * @return array
     */
    public function getVotingRounds() {
        return $this->votingRounds;
    }

    /**
     * Returns an array of all the current votes
     * 
     * @return array
     */
    public function getCurrentVotes() {
        return $this->votingRounds[0];
    }

    /**
     * Returns an array of all the current votes
     *
     *
     */
    public function getVotesCount() {
        return count($this->votingRounds[0]);
    }
    
    /**
     * Function to add a vote
     * 
     * 
     */
    public function addVote($vote) {
        if(!isset($_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'][0])) {
            $_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'][0] = array();
        }
        $_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'][0][] = $vote;
        $this->votingRounds = $_SESSION['POINTS_POKER'][$this->sessionID]['VOTES'];
    }
    
    /**
     * Returns an integer of the current State.
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
    public function getStory() {
        return $this->story->getStoryDescription();
    }
    
    
    /**
     * Returns the array of possible voting options
     *
     * @return array
     */
    public function getVotingOptions() {
        return $this->votingOptions;
    }
    

    /*
     * Set the points poker story
     *
     * @var story
     */
    private function setStory($story) {
        $_SESSION['POINTS_POKER'][$this->sessionID]['STORY'] = $story;
        $this->story = new \PointsPoker\Story\story($story);
    }

    /*
     * Set the points poker result
     *
     * @var int
     */
    private function setEstimate($estimate) {
        $_SESSION['POINTS_POKER'][$this->sessionID]['RESULT'] = $estimate;
        $this->estimate = intval($estimate);
    }

    /**
     * Returns the agreed story estimate
     *
     * @return int
     */
    public function getEstimate() {
        return $this->estimate;
    }

    /**
     * Returns the trailing for a number
     *
     * @return string
     */
    private function getSuffics($num) {
        $trailingNo = substr($num,-1);

        if($trailingNo == 1) {
            return "st";
        } else if($trailingNo == 2) {
            return "nd";
        } else if($trailingNo == 3) {
            return "rd";
        }

        return "th";
    }

}