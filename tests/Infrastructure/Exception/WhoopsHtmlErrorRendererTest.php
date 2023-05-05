<?php

namespace App\Tests\Infrastructure\Exception;

use App\Infrastructure\Exception\WhoopsHtmlErrorRenderer;
use App\Tests\ContainerTestCase;

class WhoopsHtmlErrorRendererTest extends ContainerTestCase
{
    public function testInvokeRuntimeException(): void
    {
        $exception = new \RuntimeException('Oops..');
        $renderer = $this->getContainer()->get(WhoopsHtmlErrorRenderer::class);

        $this->assertEmpty($renderer->__invoke($exception, true));
    }
}
