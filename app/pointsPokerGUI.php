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

        include SITE_ROOT.'templates/footer.php';
    }       
    
    /*
     * Shows a summary of all the user votes submit
     *
     * 
     */
    private function showVoteSummary() {

        $votes = $this->storyClass->getVotes();

        $html = "<h4>User Votes:</h4>";
        

        $html .= "<ol>";
        foreach($votes as $vote) {
            $html .= "<li>".$vote."</li>";
        }
        $html .= "</ol>";

        echo $html;        
        
    }    
    
    
    /*
     * Displays the selected overall story points
     *
     * 
     */
    private function showFinalPoints() {
        
        $html = "<h2>Overall Story Points: ". $this->storyClass->getResult()."</h2>";
        
        echo $html;
        
    }
    
    /*
     * Displays the relevant buttons depending on the state of the system
     *
     * 
     */
    private function showButtons() {
        $html = '<div class="poker-buttons-pane">';
        if($this->storyClass->getState() === pointsPokerState::VOTING) {
            if($this->storyClass->getVotesCount()) {
                $html .= "<a href='?end_voting=1' class='poker-button btn btn-success'>Finish Voting</a>";
            }
        } 
        
        $html .= "<a href='?reset=".$this->storyClass->getSessionID()."' class='poker-button btn btn-danger'>Reset</a>";
        
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
        if($this->storyClass->getState() === pointsPokerState::DECISION) {
            
            $html = "<br/><h4>Final Voting Decision</h4>";
            $param = 'decision';
            
        } else {
            
            $html = "<br/><h4>Vote options:</h4> ";
        }

        $html .= "<div class='btn-group'>";
        foreach($this->storyClass->getVotingOptions() as $id => $option) {
            
            $html .= "<a href='?$param=".$option->getKey()."' class='btn btn-default' >".$option->getValue()."</a>";
            
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
        <div class='well'>". nl2br($this->storyClass->getUserStory()) . "</div>
        <h4>".$this->storyClass->getVotesCount()." Votes logged</h4>";

        echo $html;
        
    }    

}