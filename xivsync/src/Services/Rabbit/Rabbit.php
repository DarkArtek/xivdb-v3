<?php

namespace App\Services\Rabbit;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Post messages to rabbit mq
 *
 * @package App\Services\Rabbit
 */
class Rabbit
{
    private const QUEUE_OPTIONS = [
        'passive' => false,
        'durable' => false,
        'exclusive' => false,
        'auto_delete' => false,
        'nowait' => false,
        'no_local' => false,
        'no_ack' => false,
    ];

    /** @var AMQPStreamConnection */
    private $connection;

    /** @var string */
    private $queue;

    /**
     * Connect to a queue and return this class
     */
    public function connect(string $queue): Rabbit
    {
        $this->connection = new AMQPStreamConnection(
            getenv('API_RABBIT_IP'),
            getenv('API_RABBIT_PORT'),
            getenv('API_RABBIT_USERNAME'),
            getenv('API_RABBIT_PASSWORD')
        );

        $this->queue = $queue;
        return $this;
    }

    /**
     * Close the connection
     */
    public function close()
    {
        $this->connection->close();
    }

    /**
     * Send a message to the queue
     */
    public function sendMessage(string $message)
    {
        $message = new AMQPMessage($message);

        $channel = $this->connection->channel();
        $channel->queue_declare(
            $this->queue,
            self::QUEUE_OPTIONS['passive'],
            self::QUEUE_OPTIONS['durable'],
            self::QUEUE_OPTIONS['exclusive'],
            self::QUEUE_OPTIONS['auto_delete'],
            self::QUEUE_OPTIONS['nowait']
        );

        $channel->basic_publish($message, '', $this->queue);
        return $this;
    }
}
