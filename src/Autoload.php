<?php
/**
 * Autoloader for legacy PHP projects
 */

spl_autoload_register(function ($name) {
    $file = dirname(__FILE__) . '/' . $name . '.php';
    //if (file_exists($file)) {
    if (file_exists($name . '.php')) {
        //require $file;
        require $name . '.php';

        return true;
    }

    return false;
    //echo "Want to load $name.\n";
    //throw new Exception("Unable to load $name.");
});
