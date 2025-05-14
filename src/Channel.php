<?php

namespace Wilkques\Log;

use Wilkques\Container\Container;
use Wilkques\Helpers\Arrays;

class Channel
{
    /**
     * @var string
     */
    protected $channel = 'file';

    /** 
     * @var Container
     */
    protected $container;

    /**
     * driver resolver
     * 
     * @var array
     */
    protected $channels = array();

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $channel
     * 
     * @return \Wilkques\Log\Channels\File
     */
    public function channel($channel = 'system')
    {
        $this->channel = $channel;

        switch ($channel) {
            default:
                if ($this->hasnotChannel($channel)) {
                    Arrays::set($this->channels, $channel, $this->container->make('\\Wilkques\\Log\\Channels\\File'));
                }

                return $this->logChannel($channel);
                break;
        }

        throw new \RuntimeException("Channel {$channel} does not exist");
    }

    /**
     * @param string|null $channel
     * 
     * @return \Wilkques\Log\Channels\File
     */
    public function logChannel($channel = null)
    {
        $channel = $channel ?: $this->channel;

        return Arrays::get($this->channels, $channel);
    }

    /**
     * @param string|null $channel
     * 
     * @return bool
     */
    public function hasChannel($channel = null)
    {
        return (bool) $this->logChannel($channel);
    }

    /**
     * @param string|null $channel
     * 
     * @return bool
     */
    public function hasnotChannel($channel = null)
    {
        return ! $this->hasChannel($channel);
    }
}
