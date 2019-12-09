<?php

namespace YotiTest\Http;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
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
}
