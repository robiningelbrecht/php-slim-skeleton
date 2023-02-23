<?php

namespace App\Tests\Infrastructure\AMQP\RunUnitTester;

use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;

class RunUnitTesterCommandHandler implements CommandHandler
{
    public function handle(DomainCommand $command): void
    {
        // TODO: Implement handle() method.
    }
}
