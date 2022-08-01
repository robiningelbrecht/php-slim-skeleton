<?php

namespace App\Infrastructure\CQRS;

interface CommandHandler
{
    public function handle(DomainCommand $command): void;
}