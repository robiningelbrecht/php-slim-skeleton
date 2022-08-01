<?php

namespace App\Infrastructure\CQRS;

use App\Infrastructure\AMQP\Envelope;

abstract class DomainCommand implements Envelope
{

}