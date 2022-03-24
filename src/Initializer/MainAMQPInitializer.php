<?php

namespace Addequatte\ImageService\Initializer;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use const __BASE_DIR__;

class MainAMQPInitializer implements AMQPInitializerInterface
{
    /**
     * @return AMQPChannel
     */
    public static function init(): AMQPChannel
    {
        $channel = self::connect();

        self::exchangeDeclare($channel);

        self::queuesDeclare($channel);

        return $channel;
    }

    /**
     * @return AMQPChannel
     */
    private static function connect(): AMQPChannel
    {
        $connection = new AMQPStreamConnection(
            getenv('AMQ_HOST'),
            getenv('AMQ_PORT'),
            getenv('AMQ_USER'),
            getenv('AMQ_PASSWORD'),
            getenv('AMQ_VHOST')
        );

        return  $connection->channel();
    }

    /**
     * @param AMQPChannel $channel
     * @return void
     */
    private static function exchangeDeclare(AMQPChannel $channel): void
    {
        $channel->exchange_declare(
            getenv('AMQ_EXCHANGE'),
            getenv('AMQ_EXCHANGE_TYPE'),
            getenv('AMQ_EXCHANGE_PASSIVE') == 'true',
            getenv('AMQ_EXCHANGE_DURABLE') == 'true',
            getenv('AMQ_EXCHANGE_AUTO_DELETE') == 'true',
            getenv('AMQ_EXCHANGE_INTERNAL') == 'true',
            getenv('AMQ_EXCHANGE_NOAWAIT') == 'true'
        );
    }

    /**
     * @param AMQPChannel $channel
     * @return void
     */
    private static function queuesDeclare(AMQPChannel $channel): void
    {
        foreach (require __BASE_DIR__ . '/src/config/queues.php' as $qName => $queue) {

            $channel->queue_declare(...[...['queue' => $qName], ...$queue['params']]);

            $channel->queue_bind($qName, getenv('AMQ_EXCHANGE'), $qName);
        }
    }
}