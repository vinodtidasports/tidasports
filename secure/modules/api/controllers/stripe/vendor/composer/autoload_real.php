<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitaeaae36fce0c87fe58a0f8d4a75d534c
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitaeaae36fce0c87fe58a0f8d4a75d534c', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitaeaae36fce0c87fe58a0f8d4a75d534c', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitaeaae36fce0c87fe58a0f8d4a75d534c::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
