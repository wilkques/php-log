<?php

namespace Wilkques\Log\Tests\Channels;

use Wilkques\Filesystem\Filesystem;
use Wilkques\Log\Channels\File;
use Wilkques\Log\Tests\TestCase;

class FileTest extends TestCase
{
    /**
     * @return File
     */
    protected function file($directory = null, $filePermission = null)
    {
        return new File(new Filesystem, $directory ?: $this->tmpDir, $filePermission);
    }

    public function testDefaultDirectoryAndLogName()
    {
        $file = new File(new Filesystem);

        $this->assertSame('./storage/logs', $file->getDirectory());
        $this->assertSame('./storage/logs/system.log', $file->getCompilerPath());
    }

    public function testSetDirectoryAndLogName()
    {
        $file = $this->file();

        $file->logName('custom.log');

        $this->assertSame($this->tmpDir, $file->getDirectory());
        $this->assertSame($this->tmpDir . '/custom.log', $file->getCompilerPath());
    }

    public function testSetAndGetFilePermission()
    {
        $file = $this->file();

        $this->assertNull($file->getFilePermission());

        $file->setFilePermission(0644);

        $this->assertSame(0644, $file->getFilePermission());
    }

    public function testLoggerWritesMessageToFileAndCreatesDirectory()
    {
        $nested = $this->tmpDir . '/nested/dir';

        $file = $this->file($nested);

        $file->logName('app.log');

        $result = $file->logger('first line' . PHP_EOL);

        $this->assertTrue($result);
        $this->assertFileExists($nested . '/app.log');
        $this->assertSame('first line' . PHP_EOL, file_get_contents($nested . '/app.log'));
    }

    public function testLoggerAppendsRatherThanOverwrites()
    {
        $file = $this->file();

        $file->logName('append.log');

        $file->logger('one' . PHP_EOL);
        $file->logger('two' . PHP_EOL);

        $this->assertSame('one' . PHP_EOL . 'two' . PHP_EOL, file_get_contents($this->logPath('append.log')));
    }
}
