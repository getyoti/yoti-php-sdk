<?php

declare(strict_types=1);

namespace Yoti\Test\Http;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\Http\Client;
use Yoti\Test\TestCase;

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
     * @dataProvider clientExceptionDataProvider
     */
    public function testSendRequestThrowsClientException($guzzleException, $expectedException)
    {
        try {
            $this->sendRequestAndThrow($guzzleException);
            $this->fail('Exception was not thrown');
        } catch (ClientExceptionInterface $e) {
            $this->assertInstanceOf($expectedException, $e);
            $this->assertSame($guzzleException, $e->getPrevious());
            $this->assertEquals($guzzleException->getMessage(), $e->getMessage());
        }
    }

    /**
     * @covers ::sendRequest
     * @covers ::__construct
     *
     * @dataProvider requestAwareExceptionDataProvider
     */
    public function testSendRequestThrowsRequestAwareException($guzzleException, $expectedException)
    {
        try {
            $this->sendRequestAndThrow($guzzleException);
            $this->fail('Exception was not thrown');
        } catch (NetworkExceptionInterface | RequestExceptionInterface $e) {
            $this->assertInstanceOf($expectedException, $e);
            $this->assertInstanceOf(RequestInterface::class, $e->getRequest());
        }
    }

    /**
     * @covers ::sendRequest
     * @covers ::__construct
     *
     * @dataProvider clientExceptionDataProvider
     */
    public function testSendRequestThrowsException($guzzleException, $expectedException)
    {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($guzzleException->getMessage());
        $this->sendRequestAndThrow($guzzleException);
    }

    /**
     * Request aware exception data provider.
     */
    public function requestAwareExceptionDataProvider(): array
    {
        $someRequest = $this->createMock(Request::class);

        return [
            [
                new ConnectException('some network exception', $someRequest),
                \Yoti\Http\Exception\NetworkException::class
            ],
            [
                new RequestException('some request exception', $someRequest),
                \Yoti\Http\Exception\RequestException::class
            ]
        ];
    }

    /**
     * HTTP Client exception data provider.
     */
    public function clientExceptionDataProvider(): array
    {
        return array_merge(
            $this->requestAwareExceptionDataProvider(),
            [
                [
                    new TransferException('some client exception'),
                    \Yoti\Http\Exception\ClientException::class
                ]
            ]
        );
    }

    /**
     * @param \Throwable $exception
     *
     * @throws \Throwable
     */
    private function sendRequestAndThrow(\Throwable $exception)
    {
        $someHandler = new MockHandler([$exception]);
        $someHandlerStack = HandlerStack::create($someHandler);

        $client = new Client(['handler' => $someHandlerStack]);

        $client->sendRequest(new Request('GET', '/'));
    }
}
