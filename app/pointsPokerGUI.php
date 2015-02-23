<?php

namespace PointsPoker\UI;

require_once SITE_ROOT.'app/storyEstimation.php';
require_once SITE_ROOT.'app/storyEstimationState.php';

/**
 * Points Poker UI
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
 * Points Poker GUI Class
 *
 * @category Points Poker GUI
 * @author   Rien Sach <rien.sach@btinternet.com>
 * @link     TBC
 */

class pointsPokerGUI
{
    
    /*
     * An instance of the storyEstimation class
     *
     * @var class
     */
    private $storyEstimation;

    /**
     * Constructor
     *
     */
    public function __construct() {
        $this->storyEstimation = new \pointsPoker\storyEstimation\storyEstimation();
        $this->pointsPokerGUI();
    }
    
    /*
     * Processes the current state of the system and displays the correct screen
     *
     * 
     */
    private function pointsPokerGUI() {
        //Initially Passing POST and SESSION variables so we can upgrade this later
        if($_REQUEST) $this->storyEstimation->processInput($_REQUEST);

        $status = $this->storyEstimation->getState();

        include SITE_ROOT.'templates/header.php';
        
        switch($status) {

            case \pointsPoker\storyEstimation\storyEstimationState::VOTING:
                // Story added, show the voting buttons
                $this->showStory();
                $this->showVotingOptions();
                $this->showButtons();
                break;
            case \pointsPoker\storyEstimation\storyEstimationState::ESTIMATE:
                // Story added, votes add, show summary screen to choose vote
                $this->showStory();
                $this->showVoteSummary();
                $this->showVotingOptions();               
                $this->showButtons();
                break;
            case \pointsPoker\storyEstimation\storyEstimationState::RESULT:
                // Story added, votes add, final vote chosen, show overall
                $this->showStory();
                $this->showVoteSummary();
                $this->showFinalPoints();                
                $this->showButtons();
                break;
            default:
                // Nothing happened, show the add user story GUI
                $this->inputStory();
                break;
        
        }

        include SITE_ROOT.'templates/footer.php';
    }       
    
    /*
     * Shows a summary of all the user votes submit
     *
     * 
     */
    private function showVoteSummary() {
        //Get all the voting rounds
        $votingRounds = $this->storyEstimation->getVotingRounds();

        //Flip the array to give the votes in the voted order
        //Array Flip won't work on array of Arrays
        //$votingRounds = array_flip($votingRounds);
        $votingRoundsReveresed = array();
        foreach($votingRounds as $votes) {
            array_unshift($votingRoundsReveresed,$votes);
        }

        $html = "<h4>User Votes</h4>";

        foreach($votingRoundsReveresed as $roundNo => $votes) {
            $html .= "<h6>Round ".($roundNo+1)."</h6>";
            $html .= "<ol>";
            foreach($votes as $vote) {
                $html .= "<li>" . $vote . "</li>";
            }
            $html .= "</ol>";
        }

        echo $html;
    }    
    
    
    /*
     * Displays the selected overall story points
     *
     * 
     */
    private function showFinalPoints() {
        
        $html = "<h2>Overall Story Points Estimate: ". $this->storyEstimation->getEstimate()."</h2>";
        
        echo $html;
        
    }
    
    /*
     * Displays the relevant buttons depending on the state of the system
     *
     * 
     */
    private function showButtons() {
        $html = '<div class="poker-buttons-pane">';
        if($this->storyEstimation->getState() === \pointsPoker\storyEstimation\storyEstimationState::VOTING) {
            if($this->storyEstimation->getVotesCount()) {
                $html .= "<a href='?end_voting=1' class='poker-button btn btn-success'>Finish Voting</a>";
            }
        } else if($this->storyEstimation->getState() === \pointsPoker\storyEstimation\storyEstimationState::ESTIMATE) {
            if($this->storyEstimation->getVotesCount()) {
                $html .= "<a href='?re-vote=1' class='poker-button btn btn-info'>Re-Vote</a>";
            }
        }

        $html .= "<a href='?reset=".$this->storyEstimation->getSessionID()."' class='poker-button btn btn-danger'>Reset</a>";
        
        $html .= "</div>";
        
        echo $html;
        
    }
    
    /*
     * Displays a text box for the user to input their story
     *
     * 
     */
    private function inputStory() {
        
        $html = "<form method='post'>"
                . "<textarea id='userStory' name='userStory' class='form-control' placeholder='Enter your story...'></textarea><br/>"
                . "<input type='submit' value='Estimate' class='btn btn-default' />"
                . "</form>";
        
        echo $html;
        
    }
    
    /*
     * Displays the possible voting options
     *
     * 
     */
    private function showVotingOptions() {
        
        $param = 'vote';
        if($this->storyEstimation->getState() === \pointsPoker\storyEstimation\storyEstimationState::ESTIMATE) {
            
            $html = "<br/><h4>Final Story Estimate</h4>";
            $param = 'estimate';
            
        } else {
            
            $html = "<br/><h4>Vote options:</h4> ";
        }

        $html .= "<div class='btn-group'>";
        foreach($this->storyEstimation->getVotingOptions() as $id => $option) {
            
            $html .= "<a href='?$param=".$option->getKey()."&count=".$this->storyEstimation->getVotesCount()."' class='btn btn-default' >".$option->getValue()."</a>";

        }
        $html .= "</div>";

        echo $html;
    }
    
    /*
     * Shows the users entered user story
     *
     * 
     */
    private function showStory() {
        
        $html = "<h2>User Story: </h3>
        <div class='well'>". nl2br($this->storyEstimation->getStory()) . "</div>
        <h4>".$this->storyEstimation->getVotingRound()." Voting Round</h4>
        <h4>".$this->storyEstimation->getVotesCount()." Votes logged</h4>";

        echo $html;
        
    }    

}