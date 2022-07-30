<?php

namespace App\Infrastructure\Container;

enum Environment: string
{
    case DEV = 'dev';
    case PRODUCTION = 'production';
}