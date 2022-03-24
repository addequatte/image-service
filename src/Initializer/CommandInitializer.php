<?php

namespace Addequatte\ImageService\Initializer;

use PhpAmqpLib\Channel\AMQPChannel;
use Symfony\Component\Console\Application;
use const __BASE_DIR__;

class CommandInitializer
{
    /**
     * @param Application $application
     * @param AMQPChannel $channel
     * @return void
     */
    public static function init(Application $application, AMQPChannel $channel): void
    {
        foreach (require __BASE_DIR__ . '/src/config/commands.php' as $command => $name) {
            $application->add(new $command($channel, $name));
        }
    }
}