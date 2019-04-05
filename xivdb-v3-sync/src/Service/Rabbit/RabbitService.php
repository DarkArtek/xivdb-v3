<?php

namespace App\Service\Rabbit;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitService
{
    const DEFAULT_DURATION = 55;
    const DEFAULT_TIMEOUT = 15;
    const QUEUE_OPTIONS = [
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
    /** @var string */
    public $exception;

    /**
     * Connect to a queue and return this class
     */
    public function connect(string $queue): RabbitService
    {
        $this->connection = new AMQPStreamConnection(
            getenv('API_RABBIT_IP'),
            getenv('API_RABBIT_PORT'),
            getenv('API_RABBIT_USERNAME'),
            getenv('API_RABBIT_PASSWORD'),
            '/', false, 'AMQPLAIN', null, 'en_US',
            self::DEFAULT_TIMEOUT,
            self::DEFAULT_TIMEOUT
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
     * Read messages asynchronously, requires a class handler for processing messages
     * - If no messages are received in the "duration" period, the script will stop
     * - If the script loop continues past the "timeout" period, the script will stop
     */
    public function readMessageAsync(
        $handler,
        int $duration = self::DEFAULT_DURATION,
        int $timeout = self::DEFAULT_TIMEOUT
    ) {
        /** @var AMQPChannel $channel */
        $channel = $this->connection->channel();

        // callback function for message
        $callback = function($message) use ($channel, $handler) {
            $json = json_decode($message->body);
            $handler->handle($json);

            # when commented the message will stay in rabbitmq
            if (getenv('APP_ENV') == 'prod') {
                $channel->basic_ack($message->delivery_info['delivery_tag']);
            }
        };

        // basic message consumer
        $channel->basic_consume(
            $this->queue,
            null,
            self::QUEUE_OPTIONS['no_local'],
            self::QUEUE_OPTIONS['no_ack'],
            self::QUEUE_OPTIONS['exclusive'],
            self::QUEUE_OPTIONS['nowait'],
            $callback
        );

        // process messages
        $time = time();
        while(count($channel->callbacks)) {
            $diff = time() - $time;

            if ($diff > $duration) {
                return true;
            }

            try {
                $channel->wait(false, false, $timeout);
            } catch (\Exception $ex) {
                $this->exception = $ex;
                return true;
            }
        }

        return true;
    }

    /**
     * Read a message synchronously, this is slow
     */
    public function readMessageSync()
    {
        $message = $this->channel->basic_get($this->queue);

        if (!$message) {
            return false;
        }

        // acknowledge the message
        $this->channel->basic_ack($message->delivery_info['delivery_tag']);

        // decode
        return $message->body;
    }
}
