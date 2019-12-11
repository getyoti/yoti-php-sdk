<?php

namespace YotiTest\Http;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\Http\Client;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Http\Client
 */
class ClientTest extends TestCase
{
    /**
     * @covers ::sendRequest
     * @covers ::__construct
     */
    public function testSendRequest()
    {
        $someResponse = $this->createMock(ResponseInterface::class);
        $someHandler = new MockHandler([$someResponse]);
        $someHandlerStack = HandlerStack::create($someHandler);

        $client = new Client(['handler' => $someHandlerStack]);

        $this->assertSame(
            $someResponse,
            $client->sendRequest(new Request('GET', '/'))
        );

        $this->assertEquals(30, $someHandler->getLastOptions()['timeout']);
    }

    /**
     * @covers ::sendRequest
     * @covers ::__construct
     *
     * @expectedException \Yoti\Http\Exception\NetworkException
     * @expectedExceptionMessage some network exception
     */
    public function testSendRequestNetworkException()
    {
        $someHandler = new MockHandler([
            new ConnectException(
                'some network exception',
                $this->createMock(RequestInterface::class)
            ),
        ]);
        $someHandlerStack = HandlerStack::create($someHandler);

        $client = new Client(['handler' => $someHandlerStack]);

        $client->sendRequest(new Request('GET', '/'));
    }

    /**
     * @covers ::sendRequest
     * @covers ::__construct
     *
     * @expectedException \Yoti\Http\Exception\RequestException
     * @expectedExceptionMessage some runtime exception
     */
    public function testSendRequestRuntimeException()
    {
        $someHandler = new MockHandler([
            new \RuntimeException('some runtime exception'),
        ]);
        $someHandlerStack = HandlerStack::create($someHandler);

        $client = new Client(['handler' => $someHandlerStack]);

        $client->sendRequest(new Request('GET', '/'));
    }


    /**
     * @covers ::sendRequest
     * @covers ::__construct
     *
     * @expectedException \Yoti\Http\Exception\ClientException
     * @expectedExceptionMessage some exception
     */
    public function testSendRequestException()
    {
        $someHandler = new MockHandler([
            new \Exception('some exception'),
        ]);
        $someHandlerStack = HandlerStack::create($someHandler);

        $client = new Client(['handler' => $someHandlerStack]);

        $client->sendRequest(new Request('GET', '/'));
    }
}
