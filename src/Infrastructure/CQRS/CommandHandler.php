<?php

namespace App\Infrastructure\CQRS;

interface CommandHandler
{
    public function handle($command): void;
}