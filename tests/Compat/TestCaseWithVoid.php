<?php

namespace Wilkques\Log\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Wilkques\Container\Container;
use Wilkques\Filesystem\Filesystem;
use Wilkques\Log\Channel;
use Wilkques\Log\Channels\File;
use Wilkques\Log\Log;

abstract class TestCase extends BaseTestCase
{
    /**
     * @var string
     */
    protected $tmpDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resetContainerSingleton();

        $this->tmpDir = sys_get_temp_dir() . '/wilkques-log-tests-' . uniqid();

        mkdir($this->tmpDir, 0777, true);
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->tmpDir);

        $this->resetContainerSingleton();

        parent::tearDown();
    }

    /**
     * Build a real Log instance wired to a real File channel pointed at
     * this test's tmpDir, through the same Container-based resolution path
     * Log::make()/the logger() helper use in production — not constructed
     * directly, so a regression in that wiring (e.g. Channel::channel()'s
     * container->make() call) would actually be caught here.
     *
     * @param string $logName
     *
     * @return Log
     */
    protected function makeLog($logName = 'test.log')
    {
        $container = Container::getInstance();

        $file = new File(new Filesystem, $this->tmpDir);

        $file->logName($logName);

        $container->instance('\\Wilkques\\Log\\Channels\\File', $file);

        return Log::make();
    }

    /**
     * @param string $logName
     *
     * @return string
     */
    protected function logPath($logName = 'test.log')
    {
        return $this->tmpDir . '/' . $logName;
    }

    /**
     * Reset the Container's static singleton between tests so that a test
     * exercising Log::make()/the logger() helper (which resolves through
     * Container::getInstance()) can never leak state into another test.
     */
    protected function resetContainerSingleton()
    {
        if (method_exists('Wilkques\\Container\\Container', 'setInstance')) {
            Container::setInstance(null);

            return;
        }

        $reflection = new \ReflectionClass('Wilkques\\Container\\Container');

        $property = $reflection->getProperty('instance');
        $property->setAccessible(true);
        $property->setValue(null, null);
    }

    /**
     * Recursively delete a directory tree.
     *
     * @param string $dir
     *
     * @return void
     */
    protected function removeDirectory($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        $items = new \FilesystemIterator($dir);

        foreach ($items as $item) {
            if ($item->isDir() && !$item->isLink()) {
                $this->removeDirectory($item->getPathname());
            } else {
                @unlink($item->getPathname());
            }
        }

        @rmdir($dir);
    }

    /**
     * PHPUnit assertion/expectation method names that have been renamed
     * across the major versions this suite runs under (see
     * tests/bootstrap.php re: "phpunit/phpunit": "*").
     *
     * @param string $class
     *
     * @return void
     */
    protected function expectExceptionCompat($class)
    {
        if (method_exists($this, 'expectException')) {
            // PHPUnit >= 5.2
            $this->expectException($class);

            return;
        }

        // PHPUnit 4.x: no expectException() at all.
        $this->setExpectedException($class);
    }

    /**
     * assertStringContainsString() was added in PHPUnit 7.5; PHPUnit 4.8's
     * assertContains() also matches substrings within a string (a behavior
     * later versions split off into assertStringContainsString() and made
     * assertContains() itself array-only).
     */
    protected function assertStringContainsStringCompat($needle, $haystack)
    {
        if (method_exists($this, 'assertStringContainsString')) {
            $this->assertStringContainsString($needle, $haystack);

            return;
        }

        $this->assertContains($needle, $haystack);
    }

    /**
     * assertMatchesRegularExpression() replaced the now-removed
     * assertRegExp() in PHPUnit 10.
     */
    protected function assertMatchesRegExpCompat($pattern, $string)
    {
        if (method_exists($this, 'assertMatchesRegularExpression')) {
            $this->assertMatchesRegularExpression($pattern, $string);

            return;
        }

        $this->assertRegExp($pattern, $string);
    }
}
