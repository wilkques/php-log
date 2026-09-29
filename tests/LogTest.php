<?php

namespace Wilkques\Log\Tests;

class LogTest extends TestCase
{
    public function testLevelMethodWritesFormattedLineToTheFileChannel()
    {
        $log = $this->makeLog('level.log');

        $log->info('hello world');

        $this->assertFileExists($this->logPath('level.log'));
        $this->assertStringContainsStringCompat('[INFO] hello world', file_get_contents($this->logPath('level.log')));
    }

    public function testMultipleLevelCallsAppendSeparateLines()
    {
        $log = $this->makeLog('multi.log');

        $log->info('first');
        $log->error('second');

        $contents = file_get_contents($this->logPath('multi.log'));

        $this->assertStringContainsStringCompat('[INFO] first', $contents);
        $this->assertStringContainsStringCompat('[ERROR] second', $contents);
    }

    public function testForceMethodIsPassedThroughToTheUnderlyingChannel()
    {
        $log = $this->makeLog('force.log');

        $path = $log->getCompilerPath();

        $this->assertSame($this->logPath('force.log'), $path);
    }

    public function testUnknownMethodIsPassedThroughAndReturnsLogForChaining()
    {
        $log = $this->makeLog('chain.log');

        $result = $log->logName('renamed.log');

        $this->assertSame($log, $result);
    }

    public function testHelperFunctionReturnsLogInstanceWhenNoMessageGiven()
    {
        $this->makeLog('helper.log');

        $result = logger();

        $this->assertInstanceOf('Wilkques\\Log\\Log', $result);
    }

    public function testHelperFunctionWritesInfoLevelWhenMessageGiven()
    {
        $this->makeLog('helper2.log');

        logger('via helper');

        $this->assertStringContainsStringCompat('[INFO] via helper', file_get_contents($this->logPath('helper2.log')));
    }
}
