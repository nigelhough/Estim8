<?php

spl_autoload_register(function ($class) {
    if(preg_match("/^votingoptions/i", $class)) {
        include_once  SITE_ROOT . 'app' . DIRECTORY_SEPARATOR . str_replace("\\",DIRECTORY_SEPARATOR,$class) . '.php';
    }
});