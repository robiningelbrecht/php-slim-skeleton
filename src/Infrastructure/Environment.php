<?php

namespace App\Infrastructure;

enum Environment: string
{
    case DEV = 'dev';
    case PRODUCTION = 'production';
}