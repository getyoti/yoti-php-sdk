<?php

namespace YotiTest\Http\Exception;

use Psr\Http\Message\RequestInterface;
use Yoti\Http\Exception\RequestException;
use YotiTest\TestCase;

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
