<?php

// This is the standalone package repository, so the package root is one
// directory up and its own Composer autoloader lives at
// ../vendor/autoload.php. Run `composer install` at the package root
// before running the suite.
require __DIR__ . '/../vendor/autoload.php';

// The autoloader above only knows about "Wilkques\Log\" -> "src/". Register
// a second, PSR-4-ish autoloader for this package's own test fixtures/
// classes so that "Wilkques\Log\Tests\Foo\Bar" resolves to
// "tests/Foo/Bar.php" relative to this file.
spl_autoload_register(function ($class) {
    $prefix = 'Wilkques\\Log\\Tests\\';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relative = substr($class, strlen($prefix));

    $file = __DIR__ . '/' . str_replace('\\', '/', $relative) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

// composer.json's require-dev pins "phpunit/phpunit": "*", so composer
// resolves whichever major version the running PHP can actually install:
// PHPUnit 4.8 on PHP 5.3, up through whatever's newest on 8.3+. See
// wilkques/filesystem's tests/bootstrap.php, which this file mirrors, for
// why both compat shims below are necessary (compile-time syntax, not
// something a runtime check alone can paper over).
if (!class_exists('PHPUnit\\Framework\\TestCase') && class_exists('PHPUnit_Framework_TestCase')) {
    class_alias('PHPUnit_Framework_TestCase', 'PHPUnit\\Framework\\TestCase');
}

if (!defined('WILKQUES_LOG_TESTS_SETUP_NEEDS_VOID')) {
    $needsVoid = false;

    if (method_exists('ReflectionMethod', 'hasReturnType')) {
        $setUpReflection = new ReflectionMethod('PHPUnit\\Framework\\TestCase', 'setUp');
        $needsVoid = $setUpReflection->hasReturnType();
    }

    define('WILKQUES_LOG_TESTS_SETUP_NEEDS_VOID', $needsVoid);
}
