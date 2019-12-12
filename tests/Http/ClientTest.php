<?php

namespace YotiTest\Http;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\SeekException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
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
    public function testSendRequestThrowsNetworkException()
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
     *
     * @dataProvider requestExceptionDataProvider
     */
    public function testSendRequestThrowsRequestException(\Exception $someRequestException)
    {
        $this->expectExceptionMessage($someRequestException->getMessage());

        $someHandler = new MockHandler([$someRequestException]);
        $someHandlerStack = HandlerStack::create($someHandler);

        $client = new Client(['handler' => $someHandlerStack]);

        $client->sendRequest(new Request('GET', '/'));
    }

    /**
     * Provides request exceptions.
     *
     * @return array
     */
    public function requestExceptionDataProvider()
    {
        return [
            [new TransferException('some request exception')],
            [new SeekException($this->createMock(StreamInterface::class), 0, 'some seek exception')],
        ];
    }
}
