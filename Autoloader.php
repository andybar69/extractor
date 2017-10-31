<?php
namespace Extractor;

class Autoloader
{
    public static function register($prepend = false)
    {
        if (version_compare(phpversion(), '5.3.0', '>=')) {
            spl_autoload_register(array(new self, 'autoload'), true, $prepend);
        } else {
            spl_autoload_register(array(new self, 'autoload'));
        }
    }

    public static function autoload($class)
    {
        $root = dirname(__DIR__);   // get the parent directory
        $file = $root . '/' . str_replace('\\', '/', ucfirst($class ) ) . '.php';
        //echo $file.'<br>';
        if (file_exists($file) && is_readable($file)) {
            require $root . '/'  . str_replace('\\', '/', ucfirst( $class) ) . '.php';
        }
    }

}