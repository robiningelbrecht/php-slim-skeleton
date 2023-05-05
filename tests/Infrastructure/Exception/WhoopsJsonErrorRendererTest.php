<?php

namespace App\Tests\Infrastructure\Exception;

use App\Infrastructure\Exception\WhoopsJsonErrorRenderer;
use App\Tests\ContainerTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class WhoopsJsonErrorRendererTest extends ContainerTestCase
{
    use MatchesSnapshots;

    public function testInvokeRuntimeException(): void
    {
        $exception = new \RuntimeException('Oops..');
        $renderer = $this->getContainer()->get(WhoopsJsonErrorRenderer::class);

        $this->assertJson($renderer->__invoke($exception, true));
    }
}
