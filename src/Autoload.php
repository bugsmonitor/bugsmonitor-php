<?php
/**
 * Autoloader for legacy PHP projects
 */

spl_autoload_register(function ($n) {
    $name = explode('\\', $n);
    $name = end($name);
    $file = dirname(__FILE__) . '/' . $name . '.php';

    if (file_exists($file)) {
        require $file;

        return true;
    }

    return false;
});
