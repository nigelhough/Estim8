<?php

namespace estim8\Story;

/**
 * Story
 *
 * A User story to be estimated
 *
 * PHP version 5
 *
 * @category Points Poker
 * @author   Nigel Hough <nigel@nigelhough.co.uk>
 */

/**
 * Story
 *
 * @category Points Poker
 * @author   Nigel Hough <nigel@nigelhough.co.uk>
 */
class story
{

    /**
     * ID for the current Story
     *
     * @var string
     */
    private $storyID = null;


    /**
     * Story Description
     *
     * @var string
     */
    private $storyDescription = "";

    /**
     * Constructor
     *
     */
    function __construct($storyDescription)
    {
        $this->storyID = md5(uniqid());
        $this->setStoryDescription($storyDescription);
    }

    /**
     * Returns story description
     *
     * @return string
     */
    public function getStoryDescription() {
        return $this->storyDescription;
    }

    private function setStoryDescription($description) {
        $this->storyDescription = $description;
    }
}