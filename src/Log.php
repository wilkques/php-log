<?php

namespace Wilkques\Log;

class Log
{
    /**
     * log name
     * 
     * @var string
     */
    protected $name = 'system.log';

    /**
     * log path
     * 
     * @var string
     */
    protected $path = './storage/logs';

    /**
     * file open handle
     * 
     * @var resource|false
     */
    protected $handle;

    /**
     * @param string $path
     * 
     * @return static
     */
    public function path($path = './storage/logs')
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param string $logName
     * 
     * @return static
     */
    public function logName($logName = 'system.log')
    {
        $this->name = $logName;

        return $this;
    }

    /**
     * @param string $message
     * @param mixed  $arguments
     * 
     * @return static
     */
    public function info($message, $arguments = array())
    {
        return $this->logger(
            $this->content(
                'info',
                array(
                    $message, json_encode($arguments)
                )
            )
        );
    }

    /**
     * @param string $message
     * @param mixed  $arguments
     * 
     * @return static
     */
    public function debug($message, $arguments = array())
    {
        return $this->logger(
            $this->content(
                'debug',
                array(
                    $message, json_encode($arguments)
                )
            )
        );
    }

    /**
     * @param string $message
     * @param mixed  $arguments
     * 
     * @return static
     */
    public function warning($message, $arguments = array())
    {
        return $this->logger(
            $this->content(
                'warning',
                array(
                    $message, json_encode($arguments)
                )
            )
        );
    }

    /**
     * @param string|\Exception $message
     * @param string $context
     * 
     * @return static
     */
    public function error($message, $content = '')
    {
        $isException = false;

        $params = array(
            $message, $content, $message
        );

        if ($message instanceof \Exception) {
            $isException = true;

            array_shift($params);

            $params = $this->params($params, $message);
        }

        return $this->logger(
            $this->content('error', $params, $isException)
        );
    }

    /**
     * @param string|\Exception $message
     * @param string $content
     * 
     * @return static
     */
    public function critical($message, $content = '')
    {
        $isException = false;

        $params = array(
            $message, $content, $message
        );

        if ($message instanceof \Exception) {
            $isException = true;

            array_shift($params);

            $params = $this->params($params, $message);
        }

        return $this->logger(
            $this->content('critical', $params, $isException)
        );
    }

    /**
     * @param array $params
     * @param \Exception $exception
     * 
     * @return array
     */
    public function params($params = array(), $exception = null)
    {
        if (!$exception) {
            return $params;
        }

        array_unshift(
            $params,
            $exception->getMessage(),
            $exception->getCode(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );

        return $params;
    }

    /**
     * @param string $message
     * 
     * @return static
     */
    public function logger($message)
    {
        return $this->fopen()->write($message);
    }

    /**
     * @param string $logLevel
     * @param array $params
     * @param bool|false $isException
     * 
     * @return string
     */
    public function content($logLevel, $params = array(), $isException = false)
    {
        array_unshift($params, strtoupper($logLevel));

        return $this->replaceContent(
            $this->contentFormat($logLevel, $isException),
            $params
        );
    }

    /**
     * @param string $contentFormat
     * @param array $params
     * 
     * @return string
     */
    public function replaceContent($contentFormat, $params = array())
    {
        array_unshift($params, $contentFormat);

        return call_user_func_array('sprintf', $params);
    }

    /**
     * @param string $logLevel
     * @param bool $isException
     * 
     * @return string
     */
    public function contentFormat($logLevel, $isException = false)
    {
        if ($isException) {
            return <<<CONTENT
[%s] : %s
(code:%s)
%s:(%s)
[StackTrace]
%s
%s
CONTENT;
        }

        switch ($logLevel) {
            case 'error':
            case 'critical':
                return <<<CONTENT
[%s] : %s 
%s
%s
CONTENT;
                break;
            default:
                return <<<CONTENT
[%s] : %s 
[Information] 
#0 arguments: 
%s
%s
CONTENT;
                break;
        }
    }

    /**
     * @return string
     */
    public function getCompilerPath()
    {
        return $this->path . '/' . $this->name;
    }

    /**
     * @return static
     */
    public function fopen()
    {
        if (!is_dir($this->path)) {
            mkdir($this->path, 0664);
        }

        $this->handle = fopen($this->getCompilerPath(), 'a');

        return $this;
    }

    /**
     * @param string $message
     * 
     * @return static
     */
    public function write($message)
    {
        fwrite($this->handle, "[" . date('Y-m-d G:i:s') . "] " . print_r($message, true) . "\n");

        return $this;
    }


    public function __destruct()
    {
        if ($this->handle) {
            fclose($this->handle);
        }
    }
}
