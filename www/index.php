<?php
    //Error Reporting should be turned on in dev server config
    //These should never be pushed to production
    //Perhaps need a config or environment settings
    error_reporting(E_ALL);

    //Set Project Path as a constant
    define('SITE_ROOT',substr(__DIR__,0,strrpos(__DIR__,DIRECTORY_SEPARATOR)+1));

    require_once '../include/autoload.php';

    require_once SITE_ROOT.'app/pointsPokerGUI.php';

    $ui = new pointsPokerGUI();



