<?php

namespace Addequatte\ImageService\Initializer;

use PhpAmqpLib\Channel\AMQPChannel;

interface AMQPInitializerInterface
{
    public static function init(): AMQPChannel;
}