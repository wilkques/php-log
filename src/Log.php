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
     * @var array
     */
    protected $levels = array(
        'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug',
    );

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return static
     */
    public static function make()
    {
        return (new \Wilkques\Container\Container)->make('\\Wilkques\\Log\\Log');
    }

    public function __call($method, $arguments)
    {
        /** @var \Wilkques\Log\Channel */
        $channel = $this->container->make('\\Wilkques\\Log\\Channel');

        // choise driver
        if ($method == 'channel') {
            return call_user_func_array(array($channel, 'channel'), $arguments);
        }

        $store = $channel->channel();

        if (in_array($method, $this->levels)) {
            $messageHandler = new MessageHandler;

            return $store->logger(
                call_user_func_array(array($messageHandler, $method), $arguments)
            );
        }

        return call_user_func_array(array($store, $method), $arguments);
    }

    public static function __callStatic($method, $arguments)
    {
        $instance = static::make();

        return call_user_func_array(array($instance, $method), $arguments);
    }
}
