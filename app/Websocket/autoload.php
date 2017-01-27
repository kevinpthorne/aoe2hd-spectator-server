<?php

spl_autoload_register(function ($fullClassName) {
    error_reporting(E_ERROR);
    $types = ['class', 'interface', 'model'];
    $parts = explode('\\', $fullClassName);
    $class = end($parts);
    $lib = true;
    foreach($types as $type) {
        if(file_exists("lib/$class.$type.php")) {
            break;
        } else if(file_exists("Components/$class.php")) {
            $lib = false;
            break;
        }
    }
    try {
        if($lib) {
            include "lib/$class.$type.php";
        } else {
            include "Components/$class.php";
        }
    } catch (\Exception $e) {
        return false;
    }
    error_reporting(E_ALL);
});