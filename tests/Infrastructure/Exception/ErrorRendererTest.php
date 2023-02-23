<?php

namespace App\Tests\Infrastructure\Exception;

use App\Tests\WebTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class ErrorRendererTest extends WebTestCase
{
    use MatchesSnapshots;

    public function testInvokeHttpNotFoundException(): void
    {
        $response = $this->getApp()->handle(
            $this->createRequest(
                'GET',
                '/404-page',
            )
        );

        $this->assertEquals(404, $response->getStatusCode());
    }
}
