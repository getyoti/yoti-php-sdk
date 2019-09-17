<?php

namespace YotiTest\Http\Curl;

use PHPUnit\Framework\TestCase;
use Yoti\Http\Curl\RequestHandler;
use Yoti\Http\Request;
use Yoti\Http\Payload;

/**
 * @coversDefaultClass \Yoti\Http\Curl\RequestHandler
 */
class RequestHandlerTest extends TestCase
{
    /**
     * Curl Observer.
     */
    public static $curl;

    /**
     * Create a Curl observer.
     */
    public function setup()
    {
        $this->mockCurl = $this->getMockBuilder(\stdClass::class)
            ->setMethods([
                'curl_init',
                'curl_exec',
                'curl_getinfo',
                'curl_error',
                'curl_setopt_array',
                'curl_setopt',
                'curl_close',
            ])
            ->getMock();

        self::$curl = $this->mockCurl;
    }

    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        $expectedUrl = 'https://www.example.com';
        $expectedBody = 'SOME CONTENT';
        $expectedMethod = 'POST';
        $expectedStatusCode = 200;
        $expectedPayloadJson = '{"some":"json"}';
        $expectedPayload = $this->createMock(Payload::class);
        $expectedHeaders = ['some' => 'header'];
        $expectedPayload->method('getPayloadJSON')->willReturn($expectedPayloadJson);

        // Mock the request.
        $request = $this->createMock(Request::class);
        $request->method('getUrl')->willReturn($expectedUrl);
        $request->method('getPayload')->willReturn($expectedPayload);
        $request->method('getHeaders')->willReturn($expectedHeaders);
        $request->method('getMethod')->willReturn($expectedMethod);

        // Observe curl functions.
        $this->mockCurl
            ->expects($this->once())
            ->method('curl_init')
            ->with($expectedUrl)
            ->willReturn('ch');

        $this->mockCurl
            ->expects($this->once())
            ->method('curl_exec')
            ->willReturn('SOME CONTENT');

        $this->mockCurl
            ->expects($this->once())
            ->method('curl_getinfo')
            ->willReturn($expectedStatusCode);

        $this->mockCurl
            ->expects($this->any())
            ->method('curl_setopt')
            ->withConsecutive(
                ['ch', CURLOPT_CUSTOMREQUEST, $expectedMethod],
                ['ch', CURLOPT_POSTFIELDS, $expectedPayloadJson]
            );

        $this->mockCurl
            ->expects($this->once())
            ->method('curl_setopt_array')
            ->with(
                'ch',
                [
                    CURLOPT_HTTPHEADER => array_map(
                        function ($name, $value) {
                            return "${name}: ${value}";
                        },
                        array_keys($expectedHeaders),
                        array_values($expectedHeaders)
                    ),
                    CURLOPT_RETURNTRANSFER => true,
                ]
            );

        $this->mockCurl
            ->expects($this->once())
            ->method('curl_close');

        // Execute request.
        $handler = new RequestHandler();
        $response = $handler->execute($request);

        // Check response.
        $this->assertEquals($expectedBody, $response->getBody());
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
    }

    /**
     * Test Curl execution error.
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage some error
     */
    public function testExecuteError()
    {
        $request = $this->createMock(Request::class);

        $this->mockCurl
            ->expects($this->once())
            ->method('curl_exec')
            ->willReturn(false);

        $this->mockCurl
            ->expects($this->any())
            ->method('curl_error')
            ->willReturn('some error');

        $this->mockCurl
            ->expects($this->once())
            ->method('curl_close');

        $handler = new RequestHandler();
        $handler->execute($request);
    }
}

/**
 * Mock Curl functions in the \Yoti\Http\Curl namespace.
 */
namespace Yoti\Http\Curl;

use YotiTest\Http\Curl\RequestHandlerTest;

function curl_exec($ch)
{
    return RequestHandlerTest::$curl->curl_exec($ch);
}

function curl_getinfo($ch, $opt = null)
{
    return RequestHandlerTest::$curl->curl_getinfo($ch, $opt);
}

function curl_error($ch)
{
    return RequestHandlerTest::$curl->curl_error($ch);
}

function curl_init($url)
{
    return RequestHandlerTest::$curl->curl_init($url);
}

function curl_setopt_array($ch, $options)
{
    return RequestHandlerTest::$curl->curl_setopt_array($ch, $options);
}

function curl_setopt($ch, $option, $value)
{
    return RequestHandlerTest::$curl->curl_setopt($ch, $option, $value);
}

function curl_close($ch)
{
    return RequestHandlerTest::$curl->curl_close($ch);
}
