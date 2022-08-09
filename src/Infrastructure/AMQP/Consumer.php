<?php

namespace App\Infrastructure\AMQP;

use App\Infrastructure\AMQP\Queue\Queue;
use App\Infrastructure\AMQP\Worker\WorkerMaxLifeTimeOrIterationsExceeded;
use Doctrine\DBAL\Exception\ConnectionLost;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class Consumer
{
    private ?AMQPChannel $channel = null;

    public function __construct(
        private readonly AMQPStreamConnectionFactory $AMQPStreamConnectionFactory,
        private readonly AMQPChannelFactory $AMQPChannelFactory,
    ) {
    }

    public function __destruct()
    {
        $this->channel?->close();
    }

    public function consume(Queue $queue): void
    {
        $channel = $this->AMQPChannelFactory->getForQueue($queue);

        $callback = static function (AMQPMessage $message) use ($queue) {
            Consumer::consumeCallback($message, $queue);
        };

        try {
            $channel->basic_consume($queue->getName(), '', false, false, false, false, $callback);

            while ($channel->is_open()) {
                $channel->wait();
            }
        } catch (WorkerMaxLifeTimeOrIterationsExceeded|ConnectionLost) {
            $channel->close();
            $this->AMQPStreamConnectionFactory->get()->close();
        }
    }

    public static function consumeCallback(
        AMQPMessage $message,
        Queue $queue): void
    {
        $worker = $queue->getWorker();
        $envelope = unserialize($message->getBody());

        try {
            if ($worker->maxLifeTimeReached() || $worker->maxIterationsReached()) {
                throw new WorkerMaxLifeTimeOrIterationsExceeded();
            }

            $worker->processMessage($envelope, $message);
            $message->getChannel()?->basic_ack($message->getDeliveryTag());
        } catch (WorkerMaxLifeTimeOrIterationsExceeded $exception) {
            // Requeue message to make sure next consumer can process it.
            $message->getChannel()?->basic_nack($message->getDeliveryTag(), false, true);
            throw $exception;
        } catch (\Throwable $exception) {
            if (function_exists('newrelic_notice_error')) {
                newrelic_notice_error($exception);
            }

            $worker->processFailure($envelope, $message, $exception, $queue);
            // Ack the message to unblock queue. Worker should handle failed messages.
            $message->getChannel()?->basic_ack($message->getDeliveryTag());
        }
    }
}
