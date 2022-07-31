<?php

use App\Infrastructure\DependencyInjection\ConsoleCommandCompilerPass;

return [
    // Compiler pass to auto discover console commands.
    new ConsoleCommandCompilerPass(),
];