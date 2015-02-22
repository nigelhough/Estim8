<?php

spl_autoload_register(function ($class) {
    if(preg_match("/^votingoptions|utils/i", $class)) {
        $parts = explode("\\", $class);
        $className = array_pop($parts);
        $ds = DIRECTORY_SEPARATOR;

        include_once  SITE_ROOT . 'app' . $ds . strtolower(implode($ds,$parts)) . $ds . $className . '.php';
    }
});