<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 3/4/2019
 * Time: 10:23 AM
 */

namespace Modules\User\Libs\SmsFpt;

define('TECH_API_LIB_PATH', realpath(__DIR__ . '/src'));

class TechAPIAutoloader
{
    private static $isLoaded = false;


    /**
     * Load class required
     *
     * @param unknown $class
     * @return boolean
     */
    public static function loadClassLoader($class)
    {
        $classPart = explode('\\', $class);
        array_filter($classPart);

        if ($classPart[0] != 'TechAPI') {
            return false;
        }

        $classPath = TECH_API_LIB_PATH . sprintf('/%s.php', implode(DIRECTORY_SEPARATOR, $classPart));

        if (file_exists($classPath))
        {
            require_once $classPath;
            return true;
        }

        return false;
    }


    /**
     * Register Auto load class
     *
     * @return boolean
     */
    public static function register()
    {
        if (self::$isLoaded) {
            return false;
        }

        spl_autoload_register(array(__CLASS__, 'loadClassLoader'));

        self::$isLoaded = true;
    }
}