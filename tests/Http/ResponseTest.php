<?php

namespace Yoti\Test\Http;

use Yoti\Exception\base\YotiException;
use Yoti\Http\Response;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Http\Response
 */
class ResponseTest extends TestCase
{
    /** HTTP messages */
    private const BAD_REQUEST_MESSAGE = 'Failed validation - ';
    private const UNAUTHORIZED_MESSAGE = 'Failed authorization with the given key - ';
    private const INTERNAL_SERVER_MESSAGE_ERROR = 'An unexpected error occurred on the server - ';
    private const DEFAULT_MESSAGE = 'Unexpected error - ';

    /** HTTP codes */
    private const BAD_REQUEST_CODE = 400;
    private const UNAUTHORIZED_CODE = 401;
    private const INTERNAL_SERVER_CODE = 500;

    /**
     * @covers ::createYotiExceptionFromStatusCode
     */
    public function testCreateYotiExceptionWithBadRequestStatusCode(): void
    {
        $this->expectException(YotiException::class);
        $this->expectExceptionMessage(self::BAD_REQUEST_MESSAGE);

        $badRequestResponse = new \GuzzleHttp\Psr7\Response(self::BAD_REQUEST_CODE);
        Response::createYotiExceptionFromStatusCode($badRequestResponse);
    }

    /**
     * @covers ::createYotiExceptionFromStatusCode
     */
    public function testCreateYotiExceptionWithInternalServerErrorStatusCode(): void
    {
        $this->expectException(YotiException::class);
        $this->expectExceptionMessage(self::INTERNAL_SERVER_MESSAGE_ERROR);

        $internalServerErrorResponse = new \GuzzleHttp\Psr7\Response(self::INTERNAL_SERVER_CODE);
        Response::createYotiExceptionFromStatusCode($internalServerErrorResponse);
    }

    /**
     * @covers ::createYotiExceptionFromStatusCode
     */
    public function testCreateYotiExceptionWithUnauthorizedStatusCode(): void
    {
        $this->expectException(YotiException::class);
        $this->expectExceptionMessage(self::UNAUTHORIZED_MESSAGE);

        $unauthorizedResponse = new \GuzzleHttp\Psr7\Response(self::UNAUTHORIZED_CODE);
        Response::createYotiExceptionFromStatusCode($unauthorizedResponse);
    }

    /**
     * @covers ::createYotiExceptionFromStatusCode
     */
    public function testCreateYotiExceptionWithAnotherStatusCode(): void
    {
        $this->expectException(YotiException::class);
        $this->expectExceptionMessage(self::DEFAULT_MESSAGE);

        $someResponse = new \GuzzleHttp\Psr7\Response(302);
        Response::createYotiExceptionFromStatusCode($someResponse);
    }
}
