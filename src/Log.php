<?php

namespace Wilkques\Log;

use Wilkques\Container\Container;

class Log
{
    /** 
     * @var Container
     */
    protected $container;

    /**
     * @var Channel
     */
    protected $channel;

    /**
     * @var array
     */
    protected $levels = array(
        'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug',
    );

    /**
     * @param Container $container
     */
    public function __construct(Container $container, Channel $channel)
    {
        $this->container = $container;

        $this->channel = $channel;
    }

    /**
     * @return static
     */
    public static function make()
    {
        $container = Container::getInstance();

        return $container->make(__CLASS__);
    }

    public function __call($method, $arguments)
    {
        // choise driver
        if ($method == 'channel') {
            call_user_func_array(array($this->channel, 'channel'), $arguments);

            return $this;
        }

        $store = $this->channel->channel();

        if (in_array($method, $this->levels)) {
            $messageHandler = new MessageHandler;

            return $store->logger(
                call_user_func_array(array($messageHandler, $method), $arguments)
            );
        }

        call_user_func_array(array($store, $method), $arguments);

        return $this;
    }

    public static function __callStatic($method, $arguments)
    {
        $instance = static::make();

        return call_user_func_array(array($instance, $method), $arguments);
    }
}
