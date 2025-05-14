<?php

namespace Wilkques\Log;

class MessageHandler
{
    /**
     * @param mixed $message
     * 
     * @return string
     */
    public function emergency($message)
    {
        return $this->writeLog(__FUNCTION__, $message);
    }

    /**
     * @param mixed $message
     * 
     * @return string
     */
    public function alert($message)
    {
        return $this->writeLog(__FUNCTION__, $message);
    }

    /**
     * @param mixed $message
     * 
     * @return string
     */
    public function critical($message)
    {
        return $this->writeLog(__FUNCTION__, $message);
    }

    /**
     * @param mixed $message
     * 
     * @return string
     */
    public function error($message)
    {
        return $this->writeLog(__FUNCTION__, $message);
    }

    /**
     * @param mixed $message
     * 
     * @return string
     */
    public function warning($message)
    {
        return $this->writeLog(__FUNCTION__, $message);
    }

    /**
     * @param mixed $message
     * 
     * @return string
     */
    public function notice($message)
    {
        return $this->writeLog(__FUNCTION__, $message);
    }

    /**
     * @param mixed $message
     * 
     * @return string
     */
    public function info($message)
    {
        return $this->writeLog(__FUNCTION__, $message);
    }

    /**
     * @param mixed $message
     * 
     * @return string
     */
    public function debug($message)
    {
        return $this->writeLog(__FUNCTION__, $message);
    }

    /**
     * @param string $level
     * @param mixed $message
     * 
     * @return string
     */
    public function writeLog($level, $message)
    {
        return sprintf("[%s] [%s] %s%s", date('Y-m-d G:i:s'), strtoupper($level),  $this->messageFormat($message), PHP_EOL);
    }

    /**
     * @param mixed $message
     * @param array $context
     * 
     * @return string
     */
    public function messageFormat($message)
    {
        if (is_array($message)) {
            return var_export($message, true);
        } else if ($message instanceof \Exception) {
            return (string) $message;
        } else if (is_string($message)) {
        }

        return $message;
    }
}
