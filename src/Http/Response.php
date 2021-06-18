<?php

declare(strict_types=1);

namespace Yoti\Http;

use Psr\Http\Message\ResponseInterface;
use Yoti\DocScan\Exception\DocScanException;

class Response
{
    private const BAD_REQUEST_MESSAGE = 'Failed validation - ';
    private const UNAUTHORIZED_MESSAGE = 'Failed authorization with the given key - ';
    private const INTERNAL_SERVER_MESSAGE_ERROR = 'An unexpected error occurred on the server - ';
    private const DEFAULT_MESSAGE = 'Unexpected error - ';

    private const BAD_REQUEST_CODE = 400;
    private const UNAUTHORIZED_CODE = 401;
    private const INTERNAL_SERVER_CODE = 500;

    /**
     * @param ResponseInterface $response
     * @throws DocScanException
     */
    public static function createYotiExceptionFromStatusCode(ResponseInterface $response): void
    {
        switch ($response->getStatusCode()) {
            case self::BAD_REQUEST_CODE:
                $message = self::BAD_REQUEST_MESSAGE;
                break;
            case self::UNAUTHORIZED_CODE:
                $message = self::UNAUTHORIZED_MESSAGE;
                break;
            case self::INTERNAL_SERVER_CODE:
                $message = self::INTERNAL_SERVER_MESSAGE_ERROR;
                break;
            default:
                $message = self::DEFAULT_MESSAGE;
                break;
        }

        throw new DocScanException($message, $response);
    }
}
