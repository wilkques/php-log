<?php

namespace Wilkques\Log\Tests;

use Wilkques\Container\Container;
use Wilkques\Filesystem\Filesystem;
use Wilkques\Log\Channel;
use Wilkques\Log\Channels\File;

class ChannelTest extends TestCase
{
    /**
     * @return Channel
     */
    protected function channelInstance()
    {
        $container = Container::getInstance();

        $file = new File(new Filesystem, $this->tmpDir);

        $container->instance('\\Wilkques\\Log\\Channels\\File', $file);

        return new Channel($container);
    }

    public function testHasnotChannelIsTrueBeforeFirstResolution()
    {
        $channel = $this->channelInstance();

        $this->assertTrue($channel->hasnotChannel());
        $this->assertFalse($channel->hasChannel());
    }

    public function testChannelResolvesAndCachesTheFileDriver()
    {
        $channel = $this->channelInstance();

        $resolved = $channel->channel();

        $this->assertInstanceOf('Wilkques\\Log\\Channels\\File', $resolved);
        $this->assertTrue($channel->hasChannel());

        // Same channel name resolves to the same cached instance, not a
        // freshly-built one.
        $this->assertSame($resolved, $channel->channel());
    }

    public function testChannelSwitchesTheActiveChannelName()
    {
        $channel = $this->channelInstance();

        $channel->channel('primary');

        $this->assertTrue($channel->hasChannel('primary'));
        $this->assertFalse($channel->hasChannel('secondary'));

        $channel->channel('secondary');

        $this->assertTrue($channel->hasChannel('secondary'));
        // Switching to 'secondary' doesn't forget 'primary' was resolved.
        $this->assertTrue($channel->hasChannel('primary'));
    }

    public function testLogChannelDefaultsToTheCurrentlySelectedChannel()
    {
        $channel = $this->channelInstance();

        $channel->channel('primary');

        $this->assertSame($channel->logChannel('primary'), $channel->logChannel());
    }
}
