<?php

namespace Addequatte\ImageService\Command;

use PhpAmqpLib\Channel\AMQPChannel;
use Symfony\Component\Console\Command\Command;

class AbstractCommand extends Command
{

    /**
     * @param AMQPChannel $channel
     * @param string|null $name
     */
    public function __construct(private AMQPChannel $channel, string $name = null)
    {
        parent::__construct($name);
    }

    /**
     * @return AMQPChannel
     */
    protected function getChannel(): AMQPChannel
    {
        return $this->channel;
    }

    /**
     * @return void
     */
    protected function wait():void
    {
        while ($this->channel->is_open()) {
            $this->channel->wait();
        }
    }
}