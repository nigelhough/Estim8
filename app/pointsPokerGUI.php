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
 * Points Poker GUI Class
 *
 * @category Points Poker GUI
 * @author   Rien Sach <rien.sach@btinternet.com>
 * @link     TBC
 */

class pointsPokerGUI
{
    
    private $storyClass = pointsPoker;
    private $userStory;
    private $storyPointVotes;
    private $storyFinalPoints;
    
    
    
    function __construct() {
        $storyClass = new pointsPoker();
    }
    
    private function pointsPokerGUI() {
        
        if($_POST) $storyClass->processInput($_POST, $_SESSION);
        
        $status = $storyClass->getStatus();
        
        switch($status) {

            case 1:
                // Story added, show the voting buttons
                getStory();
                showStory();
                showVotingOptions();
                showButtons();
                break;
            case 2:
                // Story added, votes add, show summary screen to choose vote
                getStory();
                showStory();
                getStoryVotes();
                showSummary();    
                showVotingOptions();
                showFinalButtons();
                break;
            case 3:
                // Story added, votes add, final vote chosen, show overall
                getStory();
                showStory();
                getStoryVotes();
                showSummary();
                getFinalStoryPoints();
                showFinalPoints();
                break;
            default:
                // Nothing happened, show the add user story GUI
                inputStory();
                break;
        
        }
        
    }
    

    private function inputStory() {
        
        $html = "Enter your user story:"
                . "<form type='post'>"
                . "<textbox id='userStory' name='userStory'></textbox>"
                . "<submit />"
                . "</form>";
        
        echo $html;
    }
    
    private function showStory($userStory) {
        
        $html = "User Story: $userStory";
        
        echo $html;
    }
    
    private function getStory() {
        
        $userStory = $storyClass->getUserStory;
        
    }
    
    
    
    
    function __destruct() {
        
    }
    
    
}