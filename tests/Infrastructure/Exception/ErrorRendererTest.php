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
        $this->assertMatchesHtmlSnapshot((string) $response->getBody());
    }

    public function testInvokeRuntimeException(): void
    {
        // The routes registered in your app.
        $this->getApp()
            ->get('/exception', fn () => throw new \RuntimeException('WAW, an error!'));

        $response = $this->getApp()->handle(
            $this->createRequest(
                'GET',
                '/exception',
            )
        );

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertStringContainsString('<strong>Message:</strong> WAW, an error!</div>', (string) $response->getBody());
    }
}
