<?php

namespace YotiTest\Http\Exception;

use Psr\Http\Message\RequestInterface;
use Yoti\Http\Exception\NetworkException;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Http\Exception\NetworkException
 */
class NetworkExceptionTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::setRequest
     * @covers ::getRequest
     */
    public function testGetRequest()
    {
        $someRequest = $this->createMock(RequestInterface::class);
        $exception = new NetworkException('some message', $someRequest);

        $this->assertSame($someRequest, $exception->getRequest());
    }
}
