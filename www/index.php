<?php

namespace estim8;

//Error Reporting should be turned on in dev server config
//These should never be pushed to production
//Perhaps need a config or environment settings
error_reporting(E_ALL);

//Set Project Path as a constant
define('SITE_ROOT',substr(__DIR__,0,strrpos(__DIR__,DIRECTORY_SEPARATOR)+1));

require_once SITE_ROOT.'include/autoload.php';
require_once SITE_ROOT.'vendor/autoload.php';

//Load Config
\Utils\Config::parseConfig();

//Register Slim’s autoloader
\Slim\Slim::registerAutoloader();

session_start();

//Instantiate a Slim application:
$app = new \Slim\Slim(array(
    'templates.path' => SITE_ROOT.'/templates'
));

//Main Page
$app->get('/', function () use ($app) {
    $app->render('home.php');
});

//404
$app->notFound(function () use ($app) {
    $app->render('404.php');
});

//Estimation
$app->map('/estimate', function () use ($app) {
    require_once SITE_ROOT.'app/pointsPokerGUI.php';
    $ui = new UI\pointsPokerGUI();

})->via('GET', 'POST');

$app->get('/estimation/:sessionid', function () use ($app) {
    $app->render('home.php');
});

//Run the Slim application:
$app->run();




