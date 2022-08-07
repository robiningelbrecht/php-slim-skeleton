<?php

namespace App\Tests\Infrastructure\Exception;

use App\Infrastructure\Exception\HttpErrorHandler;
use App\Infrastructure\Exception\ShutdownHandler;
use App\Tests\ProvideHttpRequest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ShutdownHandlerTest extends TestCase
{
    use ProvideHttpRequest;

    private ShutdownHandler $shutdownHandler;
    private Request $request;
    private MockObject $errorHandler;

    public function testInvokeWithoutErrors(): void
    {
        $shutdownHandler = new ShutdownHandler(
            $this->request,
            $this->errorHandler,
            false,
        );

        $this->errorHandler
            ->expects($this->never())
            ->method('__invoke');

        $shutdownHandler();
    }

    public function testInvokeWithoutDisplayErrorDetails(): void
    {
        $shutdownHandler = new ShutdownHandler(
            $this->request,
            $this->errorHandler,
            false,
        );

        @trigger_error('test');

        $this->errorHandler
            ->expects($this->once())
            ->method('__invoke')
            ->willReturnCallback(function (
                ServerRequestInterface $request,
                \Throwable $exception) {
                $this->assertEquals($request, $this->request);
                $this->assertInstanceOf(HttpInternalServerErrorException::class, $exception);
                $this->assertEquals('An error while processing your request. Please try again later.', $exception->getMessage());

                return new Response(400);
            });

        $shutdownHandler();
    }

    /**
     * @dataProvider provideErrorLevels
     */
    public function testInvokeWithDisplayErrorDetails(int $errorLevel, string $expectedMessage): void
    {
        $shutdownHandler = new ShutdownHandler(
            $this->request,
            $this->errorHandler,
            true,
        );

        @trigger_error('An error', $errorLevel);

        $this->errorHandler
            ->expects($this->once())
            ->method('__invoke')
            ->willReturnCallback(function (ServerRequestInterface $request, \Throwable $exception) use ($expectedMessage) {
                $this->assertEquals($request, $this->request);
                $this->assertInstanceOf(HttpInternalServerErrorException::class, $exception);
                $this->assertStringContainsString($expectedMessage, $exception->getMessage());

                return new Response(400);
            });

        $shutdownHandler();
    }

    public function provideErrorLevels(): array
    {
        return [
            [
                E_USER_NOTICE,
                'NOTICE: An error',
            ],
            [
                E_USER_WARNING,
                'WARNING: An error',
            ],
            [
                E_USER_DEPRECATED,
                'ERROR: An error on line 75 in file',
                'ERROR: An error on line 75 in file',
            ],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->errorHandler = $this->createMock(HttpErrorHandler::class);
        $this->request = $this->buildRequest('GET', 'host');
    }
}
