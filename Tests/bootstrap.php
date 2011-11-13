<?php

spl_autoload_register(function($class)
{
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__.'/../'.$path.'.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
});