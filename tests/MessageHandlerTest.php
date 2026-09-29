<?php

namespace Wilkques\Log\Tests;

use Wilkques\Log\MessageHandler;

class MessageHandlerTest extends TestCase
{
    /**
     * @return MessageHandler
     */
    protected function handler()
    {
        return new MessageHandler;
    }

    public function testLevelMethodsFormatWithUppercaseLevelName()
    {
        $handler = $this->handler();

        $levels = array(
            'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug',
        );

        foreach ($levels as $level) {
            $line = $handler->{$level}('hello');

            $this->assertStringContainsStringCompat('[' . strtoupper($level) . ']', $line);
            $this->assertStringContainsStringCompat('hello', $line);
        }
    }

    public function testWriteLogFormatIncludesTimestampLevelAndMessage()
    {
        $handler = $this->handler();

        $line = $handler->writeLog('info', 'hello world');

        $this->assertStringContainsStringCompat('[INFO]', $line);
        $this->assertStringContainsStringCompat('hello world', $line);
        $this->assertStringEndsWith(PHP_EOL, $line);

        // e.g. "[2026-09-29 9:51:06] [INFO] hello world\n"
        $this->assertMatchesRegExpCompat('/^\[\d{4}-\d{2}-\d{2} \d{1,2}:\d{2}:\d{2}\] \[INFO\] hello world' . preg_quote(PHP_EOL, '/') . '$/', $line);
    }

    public function testMessageFormatReturnsStringMessageAsIs()
    {
        $handler = $this->handler();

        $this->assertSame('plain string', $handler->messageFormat('plain string'));
    }

    public function testMessageFormatExportsArrays()
    {
        $handler = $this->handler();

        $formatted = $handler->messageFormat(array('a' => 1));

        $this->assertSame(var_export(array('a' => 1), true), $formatted);
    }

    public function testMessageFormatCastsExceptionsToString()
    {
        $handler = $this->handler();

        $exception = new \Exception('boom');

        $this->assertSame((string) $exception, $handler->messageFormat($exception));
    }

    public function testMessageFormatPassesOtherScalarsThrough()
    {
        $handler = $this->handler();

        $this->assertSame(123, $handler->messageFormat(123));
    }
}
