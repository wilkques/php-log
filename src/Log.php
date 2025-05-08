<?php

namespace Wilkques\Log;

class Log
{
    /**
     * @var string
     */
    protected $channel = 'file';

    /** @var static */
    protected static $instance;

    /**
     * driver resolver
     * 
     * @var array
     */
    protected $channels = array();

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
                    $message,
                    json_encode($arguments)
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
                    $message,
                    json_encode($arguments)
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
                    $message,
                    json_encode($arguments)
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
            $message,
            $content
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
            $message,
            $content
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
     * @param string $logLevel
     * @param array $params
     * @param bool|false $isException
     * 
     * @return string
     */
    public function content($logLevel, $params = array(), $isException = false)
    {
        array_unshift($params, strtoupper($logLevel));

        return "[" . date('Y-m-d G:i:s') . "] " . $this->replaceContent(
            $this->contentFormat($logLevel, $isException),
            $params
        ) . PHP_EOL;
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
CONTENT;
                break;
            default:
                return <<<CONTENT
[%s] : %s 
[Information] 
#0 arguments: 
%s
CONTENT;
                break;
        }
    }

    /**
     * @return static
     */
    public static function make()
    {
        if (static::$instance) {
            return static::$instance;
        }

        static::$instance = new static;

        return static::$instance;
    }

    /**
     * @param string $channel
     * 
     * @return static
     */
    public static function channel($channel = 'file')
    {
        $instance = static::make();

        switch ($channel) {
            case 'file':
            default:
                $resolver = new FileLog();

                $instance->channels[$channel] = $resolver;
                break;
        }

        return $instance;
    }

    public function __call($method, $arguments)
    {
        if (empty($this->channels)) {
            $channel = static::channel($this->channel);

            return call_user_func_array(array($channel, $method), $arguments);
        }

        $channel = $this->channels[$this->channel];

        return call_user_func_array(array($channel, $method), $arguments);
    }

    public static function __callStatic($method, $arguments)
    {
        $instance = static::make();

        return call_user_func_array(array($instance, $method), $arguments);
    }
}
