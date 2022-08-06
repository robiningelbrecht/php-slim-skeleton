<?php

use App\Infrastructure\AMQP\Queue\QueueCompilerPass;
use App\Infrastructure\Console\ConsoleCommandCompilerPass;
use App\Infrastructure\CQRS\CommandHandler\CommandHandlerCompilerPass;
use App\Infrastructure\Eventing\EventListener\EventListenerCompilerPass;

return [
    // Compiler pass to auto discover console commands.
    new ConsoleCommandCompilerPass(),
    // Compiler pass to auto discover command handlers.
    new CommandHandlerCompilerPass(),
    // Compiler pass to auto discover event listeners.
    new EventListenerCompilerPass(),
    // Compiler pass to auto discover AMQP queues.
    new QueueCompilerPass(),
];