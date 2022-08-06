<?php

namespace App\Tests\Infrastructure\Exception;

use App\Infrastructure\Exception\HttpErrorHandler;
use App\Tests\ProvideHttpRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Psr7\Factory\ResponseFactory;

class HttpErrorHandlerTest extends TestCase
{
    use ProvideHttpRequest;

    private HttpErrorHandler $httpErrorHandler;

    /**
     * @dataProvider provideHttpExceptions
     */
    public function testRespondHttpException(
        ServerRequestInterface $request,
        string $httpException,
        int $expectedStatusCode,
        string $expectedReasonPhrase): void
    {
        $httpErrorHandler = $this->httpErrorHandler;

        $response = $httpErrorHandler(
            $request,
            new $httpException($request),
            true,
            false,
            false
        );

        $this->assertEquals($expectedReasonPhrase, $response->getReasonPhrase());
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
    }

    public function testRespondNoHttpException(): void
    {
        $httpErrorHandler = $this->httpErrorHandler;

        $response = $httpErrorHandler(
            $this->buildRequest('GET', 'host'),
            new \RuntimeException('A serious error'),
            true,
            false,
            false
        );

        $this->assertEquals('Internal Server Error', $response->getReasonPhrase());
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function provideHttpExceptions(): array
    {
        $request = $this->buildRequest('GET', 'host');

        return [
            [
                $request,
                HttpNotFoundException::class,
                404,
                'Not Found',
            ],
            [
                $request,
                HttpMethodNotAllowedException::class,
                405,
                'Method Not Allowed',
            ],
            [
                $request,
                HttpUnauthorizedException::class,
                401,
                'Unauthorized',
            ],
            [
                $request,
                HttpForbiddenException::class,
                403,
                'Forbidden',
            ],
            [
                $request,
                HttpBadRequestException::class,
                400,
                'Bad Request',
            ],
            [
                $request,
                HttpNotImplementedException::class,
                501,
                'Not Implemented',
            ],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->httpErrorHandler = new HttpErrorHandler(
            $this->createMock(CallableResolverInterface::class),
            new ResponseFactory()
        );
    }
}
