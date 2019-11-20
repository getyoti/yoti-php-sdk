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
     * cURL header callback.
     */
    const CURLOPT_HEADERFUNCTION = 'setResponseHeader';

    /**
     * cURL Observer.
     */
    public static $curl;

    /**
     * cURL resource.
     */
    private $someCurlResource;

    /**
     * Other cURL resource.
     */
    private $someOtherCurlResource;

    /**
     * Create a cURL observer and resource.
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

        $this->someCurlResource = curl_init();
        $this->someOtherCurlResource = curl_init();
    }

    /**
     * Clean up the cURL resource.
     */
    public function teardown()
    {
        curl_close($this->someCurlResource);
        curl_close($this->someOtherCurlResource);
    }

    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        $someUrl = 'https://www.example.com';
        $someBody = 'SOME CONTENT';
        $someMethod = 'POST';
        $someStatusCode = 200;
        $someResourceId = 0;
        $somePayloadJson = '{"some":"json"}';
        $somePayload = $this->createMock(Payload::class);
        $someRequestHeaders = ['some' => 'header'];
        $somePayload->method('getPayloadJSON')->willReturn($somePayloadJson);

        // Mock the request.
        $someRequest = $this->createMock(Request::class);
        $someRequest->method('getUrl')->willReturn($someUrl);
        $someRequest->method('getPayload')->willReturn($somePayload);
        $someRequest->method('getHeaders')->willReturn($someRequestHeaders);
        $someRequest->method('getMethod')->willReturn($someMethod);

        // Observe cURL functions.
        $this->mockCurl
            ->expects($this->once())
            ->method('curl_init')
            ->with($someUrl)
            ->willReturn($this->someCurlResource);

        $this->mockCurl
            ->expects($this->once())
            ->method('curl_exec')
            ->willReturn('SOME CONTENT');

        $this->mockCurl
            ->expects($this->any())
            ->method('curl_getinfo')
            ->will($this->returnValueMap([
                [$this->someCurlResource, CURLINFO_HTTP_CODE, $someStatusCode],
                [$this->someCurlResource, CURLOPT_PRIVATE, $someResourceId],
            ]));

        $this->mockCurl
            ->expects($this->any())
            ->method('curl_setopt')
            ->withConsecutive(
                [$this->someCurlResource, CURLOPT_CUSTOMREQUEST, $someMethod],
                [$this->someCurlResource, CURLOPT_PRIVATE, $someResourceId],
                [$this->someCurlResource, CURLOPT_POSTFIELDS, $somePayloadJson]
            );

        $this->mockCurl
            ->expects($this->once())
            ->method('curl_setopt_array')
            ->with($this->someCurlResource, $this->callback(function ($options) use ($someRequestHeaders) {
                $someHeaders = array_map(
                    function ($name, $value) {
                        return "${name}: ${value}";
                    },
                    array_keys($someRequestHeaders),
                    array_values($someRequestHeaders)
                );
                $this->assertEquals($someHeaders, $options[CURLOPT_HTTPHEADER]);
                $this->assertTrue($options[CURLOPT_RETURNTRANSFER]);
                $this->assertEquals(self::CURLOPT_HEADERFUNCTION, $options[CURLOPT_HEADERFUNCTION][1]);
                return true;
            }));

        $this->mockCurl
            ->expects($this->once())
            ->method('curl_close');

        // Execute request.
        $handler = new RequestHandler();
        $response = $handler->execute($someRequest);

        // Check response.
        $this->assertEquals($someBody, $response->getBody());
        $this->assertEquals($someStatusCode, $response->getStatusCode());
    }

    /**
     * @covers ::setOption
     * @covers ::execute
     */
    public function testSetOption()
    {
        $someRequest = $this->createMock(Request::class);
        $handler = new RequestHandler();
        $handler->setOption(CURLOPT_VERBOSE, true);

        // Observe cURL functions.
        $this->mockCurl
            ->expects($this->once())
            ->method('curl_init')
            ->willReturn($this->someCurlResource);

        $this->mockCurl
            ->expects($this->any())
            ->method('curl_setopt')
            ->withConsecutive(
                $this->any(),
                [$this->someCurlResource, CURLOPT_VERBOSE, true]
            );

        // Execute request.
        $handler->execute($someRequest);
    }

    /**
     * Test Curl execution error.
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage some error
     */
    public function testExecuteError()
    {
        $someRequest = $this->createMock(Request::class);

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
        $handler->execute($someRequest);
    }

    /**
     * @covers ::getResponseHeadersMap
     * @covers ::getResponseHeaders
     * @covers ::setResponseHeader
     * @covers ::setResourceId
     * @covers ::getResourceId
     */
    public function testHeadersCallback()
    {
        $someUrl = 'https://www.example.com';
        $someOtherUrl = 'https://other.example.com';

        $someHeaders = [
            'Some-Header: value 1',
            'Another-Header: value 2',
            'Invalid header',
            'Multi-Line-Header:part 1',
            ' part 2',
            "\tpart 3",
        ];

        $someHeadersMap = [
            'Some-Header' => 'value 1',
            'Another-Header' => 'value 2',
            'Multi-Line-Header' => 'part 1 part 2 part 3',
        ];

        $requestHandler = new RequestHandler();

        $someRequest = $this->createMock(Request::class);
        $someRequest->method('getUrl')->willReturn($someUrl);

        $someOtherRequest = $this->createMock(Request::class);
        $someOtherRequest->method('getUrl')->willReturn($someOtherUrl);

        $this->mockCurl
            ->method('curl_init')
            ->will($this->returnValueMap([
                [$someUrl, $this->someCurlResource],
                [$someOtherUrl, $this->someOtherCurlResource],
            ]));

        $this->mockCurl
            ->expects($this->exactly(2))
            ->method('curl_exec')
            ->withConsecutive(
                [$this->curlExecCallback($this->someCurlResource, $requestHandler, $someHeaders)],
                [$this->curlExecCallback($this->someOtherCurlResource, $requestHandler, [])]
            );

        $this->mockCurl
            ->expects($this->any())
            ->method('curl_setopt')
            ->withConsecutive(
                $this->any(),
                [$this->someCurlResource, CURLOPT_PRIVATE, 0],
                $this->any(),
                [$this->someOtherCurlResource, CURLOPT_PRIVATE, 1]
            );

        $this->mockCurl
            ->expects($this->any())
            ->method('curl_getinfo')
            ->will($this->returnValueMap([
                [$this->someCurlResource, CURLOPT_PRIVATE, 0],
                [$this->someOtherCurlResource, CURLOPT_PRIVATE, 1],
            ]));

        $someResponse = $requestHandler->execute($someRequest);
        $someOtherResponse = $requestHandler->execute($someOtherRequest);

        $this->assertEquals($someHeadersMap, $someResponse->getHeaders());
        $this->assertEmpty($someOtherResponse->getHeaders());
    }

    /**
     * Asserts provided cURL resource was executed and calls header callback.
     *
     * @param resource $someCurlResource
     * @param \Yoti\Http\Curl\RequestHandler $requestHandler
     * @param string[] $someHeaders
     *
     * @return PHPUnit_Framework_Constraint_Callback
     */
    private function curlExecCallback($someCurlResource, $requestHandler, $someHeaders)
    {
        return $this->callback(function ($ch) use ($someCurlResource, $requestHandler, $someHeaders) {
            $this->assertEquals($someCurlResource, $ch);

            foreach ($someHeaders as $header) {
                $this->invokeCurlHeaderCallback($requestHandler, $ch, $header);
            }

            return true;
        });
    }

    /**
     * Invoke cURL headers callback.
     *
     * @param \Yoti\Http\Curl\RequestHandler $requestHandler
     * @param resource $ch
     * @param string $header
     */
    private function invokeCurlHeaderCallback(RequestHandler $requestHandler, $ch, $header)
    {
        $reflection = new \ReflectionClass(get_class($requestHandler));
        $callback = $reflection->getMethod(self::CURLOPT_HEADERFUNCTION);
        $callback->setAccessible(true);
        $callback->invokeArgs($requestHandler, [$ch, $header]);
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
