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
    
    private $storyClass;
    private $userStory;
    private $storyPointVotes;
    private $storyFinalPoints;
    private $votingOptions = array(0 => 0, 1 => 1, 2 => 2, 3 => 3, 5 => 5, 8 => 8, 13 => 13, 20 => 20, 40 => 40, 100 => 100, 'U' => 'Unknown');

    
    public function __construct() {
        /*session_start(); var_dump($_SESSION['POINTS_POKER']);*/
        $this->storyClass = new pointsPoker(); 
        $this->pointsPokerGUI();
    }
    
    private function pointsPokerGUI() {
        //Initially Passing POST and SESSION variables so we can upgrade this later
        if($_REQUEST) $this->storyClass->processInput($_REQUEST);

        $status = $this->storyClass->getState();
        //var_dump($status);

        include SITE_ROOT.'templates/header.php';
        switch($status) {

            case pointsPokerState::VOTING:
                // Story added, show the voting buttons
                $this->getStory();
                $this->showStory();
                $this->showVotingOptions();
                $this->showButtons();
                break;
            case pointsPokerState::DECISION:
                // Story added, votes add, show summary screen to choose vote
                $this->getStory();
                $this->showStory();
                $this->getStoryVotes();
                $this->showSummary();
                $this->showVotingOptions(true);
                $this->showFinalButtons();
                break;
            case pointsPokerState::RESULT:
                // Story added, votes add, final vote chosen, show overall
                $this->getStory();
                $this->showStory();
                $this->getStoryVotes();
                $this->showSummary();
                $this->getFinalStoryPoints();
                $this->showFinalPoints();
                $this->showFinalButtons();
                break;
            default:
                // Nothing happened, show the add user story GUI
                $this->inputStory();
                break;
        
        }
        include SITE_ROOT.'templates/footer.php';
    }
    
    
    private function getStoryVotes() {
        
        $this->storyPointVotes = $this->storyClass->getVotes();
        
    }
    
    
    
    private function showSummary() {
        

        $html = "<br><br>User Votes: ";

        foreach($this->storyPointVotes as $id => $option) {
            $html .= "".$option.", ";
        }
        
        echo $html;
        
        
    }
    
    
    
    private function getFinalStoryPoints() {
        
        $this->storyFinalPoints = $this->storyClass->getResult();
    }
    
    private function showFinalButtons() {
     
        
        $html = "<br><br><a href='?reset=1'>Reset</a>";
        
        echo $html;
        
        
    }
    
    
    private function showFinalPoints() {
        
        $html = "<br><br>Overall Story Points: ". $this->storyFinalPoints;
        
        echo $html;
        
    }
    
    private function showButtons() {
        
        $html = "<br><br><a href='?end_voting=1'>Finish Voting</a> || <a href='?reset=1'>Reset</a>";
        
        echo $html;
    }
    private function inputStory() {
        
        $html = "Enter your user story:"
                . "<form method='post'>"
                . "<textarea id='userStory' name='userStory' class='form-control' placeholder='Enter your story...'></textarea><br/>"
                . "<input type='submit' value='Estimate' class='btn btn-default' />"
                . "</form>";
        
        echo $html;
    }
    
    private function showVotingOptions($final=false) {
        $param = 'vote';
        if($final) {
            $html = "<br><br>Final Voting Decsion <br> ";
            $param = 'decision';
        } else {
            $html = "<br><br>Vote options:<br> ";
        }
        foreach($this->votingOptions as $id => $option) {
            $html .= "<a href='?$param=".$id."'>".$option."</a> || ";
        }
                
        echo $html;
    }
    
    private function showStory() {
        
        $html = "User Story: ". $this->userStory;
        
        echo $html;
    }
    
    private function getStory() {

        $this->userStory = $this->storyClass->getUserStory();
        
    }

}