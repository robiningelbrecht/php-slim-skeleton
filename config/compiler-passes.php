<?php

use App\Infrastructure\Console\ConsoleCommandCompilerPass;
use App\Infrastructure\CQRS\CommandHandlerCompilerPass;

return [
    // Compiler pass to auto discover console commands.
    new ConsoleCommandCompilerPass(),
    // Compiler pass to auto discover command handlers.
    new CommandHandlerCompilerPass(),
];