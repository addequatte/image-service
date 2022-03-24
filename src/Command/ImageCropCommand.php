<?php

namespace Addequatte\ImageService\Command;

use Gregwar\Image\Image;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'image:crop',
    description: 'Cropping image',
)]
class ImageCropCommand extends AbstractCommand
{

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $channel = $this->getChannel();

        $channel->basic_consume(
            $this->getName(),
            callback: function (AMQPMessage $message) use ($channel) {

                $body = json_decode($message->body, true);

                $img = Image::open($body['in_image_path']);

                $img->zoomCrop(
                    $body['width'],
                    $body['height'],
                     'transparent',
                     (($img->width() / 2) - ($body['width']/2)),
                    0)
                    ->save($body['out_image_path'],quality: 90);

                $message->ack();
            });

        $this->wait();

        return Command::SUCCESS;
    }
}
