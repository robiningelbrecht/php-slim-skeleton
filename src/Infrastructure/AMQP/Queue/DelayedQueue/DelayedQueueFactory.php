<?php

namespace App\Infrastructure\AMQP\Queue\DelayedQueue;

use App\Infrastructure\AMQP\AMQPChannelFactory;

class DelayedQueueFactory
{
    public function __construct(
        private readonly AMQPChannelFactory $AMQPChannelFactory,
    ) {
    }

    public function buildWithDelay(int $delayInSeconds, RouteToQueue $routeToQueue = null): DelayedQueue
    {
        if (!$routeToQueue) {
            $routeToQueue = RouteToQueue::generalCommandQueue();
        }

        return new DelayedQueue(
            $routeToQueue,
            $delayInSeconds,
            $this->AMQPChannelFactory,
        );
    }
}
