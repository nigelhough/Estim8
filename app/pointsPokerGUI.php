<?php
require_once SITE_ROOT.'app/pointsPoker.php';
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
 * Points Poker GUI Class
 *
 * @category Points Poker GUI
 * @author   Rien Sach <rien.sach@btinternet.com>
 * @link     TBC
 */

class pointsPokerGUI
{
    
    /*
     * An instance of the storyClass class
     *
     * @var class
     */
    private $storyClass; 

    /**
     * Constructor
     *
     */
    public function __construct() {
        $this->storyClass = new pointsPoker(); 
        $this->pointsPokerGUI();
    }
    
    /*
     * Processes the current state of the system and displays the correct screen
     *
     * 
     */
    private function pointsPokerGUI() {
        //Initially Passing POST and SESSION variables so we can upgrade this later
        if($_REQUEST) $this->storyClass->processInput($_REQUEST);

        $status = $this->storyClass->getState();
        //var_dump($status);

        include SITE_ROOT.'templates/header.php';
        
        switch($status) {

            case pointsPokerState::VOTING:
                // Story added, show the voting buttons
                $this->showStory();
                $this->showVotingOptions();
                $this->showButtons();
                break;
            case pointsPokerState::DECISION:
                // Story added, votes add, show summary screen to choose vote
                $this->showStory();
                $this->showVoteSummary();
                $this->showVotingOptions();               
                $this->showButtons();
                break;
            case pointsPokerState::RESULT:
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
    }       
    
    /*
     * Shows a summary of all the user votes submit
     *
     * 
     */
    private function showVoteSummary() {        

        $html = "<p>User Votes:</p>";
        
        $array = $this->storyClass->getVotes();
        $first = key($array);
        
        foreach($array as $id => $option) {
            $html .= ($id === $first) ? "":", ";
            $html .= "".$option;
        }
        
        echo $html;        
        
    }    
    
    
    /*
     * Displays the selected overall story points
     *
     * 
     */
    private function showFinalPoints() {
        
        $html = "<br><br>Overall Story Points: ". $this->storyClass->getResult();
        
        echo $html;
        
    }
    
    /*
     * Displays the relevant buttons depending on the state of the system
     *
     * 
     */
    private function showButtons() {
        $html = '<br><br><p>';
        if($this->storyClass->getState() === pointsPokerState::VOTING) {
            if($this->storyClass->getVotesCount()) {
                $html .= "<a href='?end_voting=1'>Finish Voting</a> || ";
            }
        } 
        
        $html .= "<a href='?reset=".$this->storyClass->getSessionID()."'>Reset</a>";
        
        $html .= "</p>";
        
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
        if($this->storyClass->getState() === pointsPokerState::DECISION) {
            
            $html = "<br><br>Final Voting Decision <br> ";
            $param = 'decision';
            
        } else {
            
            $html = "<br><br>Vote options:<br> ";
        }
        
        foreach($this->storyClass->getVotingOptions() as $id => $option) {
            
            $html .= "<a href='?$param=".$id."'>".$option."</a> || ";
            
        }
                
        echo $html;
    }
    
    /*
     * Shows the users entered user story
     *
     * 
     */
    private function showStory() {
        
        $html = "<p>User Story: </p><p><i>". nl2br($this->storyClass->getUserStory()) . "</i></p>";
        $html .= "<p>".$this->storyClass->getVotesCount()." Votes logged";

        echo $html;
        
    }    

}