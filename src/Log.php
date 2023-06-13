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
     * 
     * @return static
     */
    public function logger($message)
    {
        return $this->fopen()->write($message);
    }

    /**
     * @param string $message
     * @param mixed  $arguments
     * 
     * @return static
     */
    public function info(string $message, $arguments = [])
    {
        return $this->logName()->logger(
            "[INFO] : {$message} \n" .
                "[Information] \n" .
                "#0 arguments: \n" . json_encode($arguments) . "\n"
        );
    }

    /**
     * @param string $message
     * @param mixed  $arguments
     * 
     * @return static
     */
    public function debug(string $message, $arguments = [])
    {
        return $this->logName()->logger(
            "[DEBUG] : {$message} \n" .
                "[Information] \n" .
                "#0 arguments: \n" . json_encode($arguments) . "\n"
        );
    }

    /**
     * @param string $message
     * @param mixed  $arguments
     * 
     * @return static
     */
    public function warning(string $message, $arguments = [])
    {
        return $this->logName()->logger(
            "[WARNING] : {$message} \n" .
                "[Information] \n" .
                "#0 arguments: \n" . json_encode($arguments) . "\n"
        );
    }

    /**
     * @param string $message
     * @param string $context
     * 
     * @return static
     */
    public function error($message, $context = '')
    {
        return $this->logName()->logger(
            "[ERROR] : {$message} \n" .
                $context
        );
    }

    /**
     * @param string $message
     * @param string $context
     * 
     * @return static
     */
    public function critical($message, $context = '')
    {
        return $this->logName()->logger(
            "[CRITICAL] : {$message} \n" .
                $context
        );
    }

    /**
     * @param \Exception $exception
     * @param string $logLevel
     * 
     * @return string
     */
    public function exceptionLog(\Exception $exception, $logLevel = 'ERROR')
    {
        $logLevel = strtoupper($logLevel);

        return "{$exception->getMessage()} \n" .
                "{$logLevel}(code:{$exception->getCode()}) \n" .
                $exception->getFile() . ":({$exception->getLine()}) \n" .
                "[stackTrace] \n" .
                $exception->getTraceAsString() . "\n";
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
