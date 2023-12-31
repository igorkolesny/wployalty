<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit380e2adcde10287119875e4656e8d7bb
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Wlr\\' => 4,
            'Wlpe\\' => 5,
            'Wll\\' => 4,
            'WPLoyalty\\' => 10,
        ),
        'V' => 
        array (
            'Valitron\\' => 9,
        ),
        'P' => 
        array (
            'ParseCsv\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Wlr\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
        'Wlpe\\' => 
        array (
            0 => __DIR__ . '/../..' . '/App/Apps/PointExpiry',
        ),
        'Wll\\' => 
        array (
            0 => __DIR__ . '/../..' . '/App/Apps/Launcher',
        ),
        'WPLoyalty\\' => 
        array (
            0 => __DIR__ . '/..' . '/wployalty/notifications/src',
        ),
        'Valitron\\' => 
        array (
            0 => __DIR__ . '/..' . '/vlucas/valitron/src/Valitron',
        ),
        'ParseCsv\\' => 
        array (
            0 => __DIR__ . '/..' . '/parsecsv/php-parsecsv/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit380e2adcde10287119875e4656e8d7bb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit380e2adcde10287119875e4656e8d7bb::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit380e2adcde10287119875e4656e8d7bb::$classMap;

        }, null, ClassLoader::class);
    }
}
