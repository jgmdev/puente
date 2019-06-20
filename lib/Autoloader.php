<?php
/**
 * @author Jefferson González
 * @license MIT
 * @link https://github.com/jgmdev/pquery Source code.
 */

namespace PQuery;

/**
 * Autoloader for PQuery.
 */
class Autoloader
{
    /**
     * The system class autoloader.
     * @param string $class_name
     */
    static function load($class_name)
    {
        $file = substr_replace(
            str_replace("\\", "/", $class_name) . ".php",
            "",
            0,
            7
        );

        if(file_exists(__DIR__ . "/" . $file))
            include(__DIR__ . "/" . $file);
    }

    /**
     * Provides an easy way to register the autoloader for you.
     */
    static function register()
    {
        spl_autoload_register(array('PQuery\Autoloader', 'load'));
    }
}

Autoloader::register();
