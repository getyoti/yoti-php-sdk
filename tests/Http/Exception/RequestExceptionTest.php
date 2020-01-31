<?php

declare(strict_types=1);

namespace Yoti\Test\Http\Exception;

use Psr\Http\Message\RequestInterface;
use Yoti\Http\Exception\RequestException;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Http\Exception\RequestException
 */
class RequestExceptionTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::setRequest
     * @covers ::getRequest
     */
    public function testGetRequest()
    {
        $someRequest = $this->createMock(RequestInterface::class);
        $exception = new RequestException('some message', $someRequest);

        $this->assertSame($someRequest, $exception->getRequest());
    }
}
