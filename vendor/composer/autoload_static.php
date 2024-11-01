<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5a9d01bb6a716bb90c8b25239f3d53e3
{
    public static $prefixLengthsPsr4 = array (
        'a' => 
        array (
            'app\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5a9d01bb6a716bb90c8b25239f3d53e3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5a9d01bb6a716bb90c8b25239f3d53e3::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5a9d01bb6a716bb90c8b25239f3d53e3::$classMap;

        }, null, ClassLoader::class);
    }
}
