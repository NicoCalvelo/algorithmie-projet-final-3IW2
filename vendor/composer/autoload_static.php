<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1301adfa748f7f9f13697b2baf96356c
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1301adfa748f7f9f13697b2baf96356c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1301adfa748f7f9f13697b2baf96356c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1301adfa748f7f9f13697b2baf96356c::$classMap;

        }, null, ClassLoader::class);
    }
}