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

    
    public function __construct() {
        $this->storyClass = new pointsPoker();
        $this->pointsPokerGUI();
    }
    
    private function pointsPokerGUI() {
        //Initially Passing POST and SESSION variables so we can upgrade this later
        if($_POST) $this->storyClass->processInput($_POST, $_SESSION);
        
        $status = $this->storyClass->getState();
        var_dump($status);

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
                $this->showVotingOptions();
                $this->showFinalButtons();
                break;
            case pointsPokerState::RESULTS:
                // Story added, votes add, final vote chosen, show overall
                $this->getStory();
                $this->showStory();
                $this->getStoryVotes();
                $this->showSummary();
                $this->getFinalStoryPoints();
                $this->showFinalPoints();
                break;
            default:
                // Nothing happened, show the add user story GUI
                $this->inputStory();
                break;
        
        }
        include SITE_ROOT.'templates/footer.php';
    }
    

    private function inputStory() {
        
        $html = "Enter your user story:"
                . "<form method='post'>"
                . "<textarea id='userStory' name='userStory' class='form-control' placeholder='Enter your story...'></textarea><br/>"
                . "<input type='submit' value='Estimate' class='btn btn-default' />"
                . "</form>";
        
        echo $html;
    }
    
    private function showStory($userStory) {
        
        $html = "User Story: $userStory";
        
        echo $html;
    }
    
    private function getStory() {

        $this->userStory = $storyClass->getUserStory();
        
    }

}