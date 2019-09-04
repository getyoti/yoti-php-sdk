<?php

namespace YotiTest\Http;

use Yoti\Http\AbstractRequestHandler;
use Yoti\Http\Payload;
use YotiTest\TestCase;
use Yoti\Util\Config;
use Yoti\Http\Request;

/**
 * @coversDefaultClass \Yoti\Http\AbstractRequestHandler
 */
class AbstractRequestHandlerTest extends TestCase
{
    /**
     * Test Base URL.
     */
    const BASE_URL = 'http://www.example.com/api/v1';

    /**
     * @covers ::sendRequest
     * @covers ::executeRequest
     */
    public function testHeaders()
    {
        $requestHandler = $this->createRequestHandler([
          '/',
          file_get_contents(PEM_FILE),
          SDK_ID,
        ]);

        $version = Config::getInstance()->get('version');

        $this->expectCorrectHeaders($requestHandler, [
          "X-Yoti-SDK-Version: PHP-{$version}",
          'X-Yoti-SDK: PHP',
          'X-Yoti-Auth-Key: ' . PEM_AUTH_KEY,
          'Content-Type: application/json',
          'Accept: application/json',
        ]);

        $requestHandler->sendRequest('/', 'GET');
    }

    /**
     * @covers ::sendRequest
     * @covers ::executeRequest
     */
    public function testCustomSdkIdentifierConstructor()
    {
        $requestHandler = $this->createRequestHandler([
          '/',
          file_get_contents(PEM_FILE),
          SDK_ID,
          'Drupal'
        ]);

        $this->expectCorrectHeaders($requestHandler, [
          "X-Yoti-SDK-Version: Drupal-2.2.1",
          'X-Yoti-SDK: Drupal',
          'X-Yoti-Auth-Key: ' . PEM_AUTH_KEY,
          'Content-Type: application/json',
          'Accept: application/json',
        ]);

        $requestHandler->sendRequest('/', 'GET');
    }

    /**
     * @covers ::sendRequest
     * @covers ::executeRequest
     * @covers ::setHeaders
     */
    public function testSetHeaders()
    {
        $request = $this->getMockBuilder(Request::class)
          ->disableOriginalConstructor()
          ->setMethods(['getHeaders'])
          ->getMock();

        $request->method('getHeaders')
          ->willReturn([
            'Custom' => 'value 1',
            'Custom-2' => 'value 2',
          ]);

        $requestHandler = $this->createRequestHandler([
          '/',
          file_get_contents(PEM_FILE),
          SDK_ID,
        ]);

        $this->expectCorrectHeaders($requestHandler, [
          "Custom: value 1",
          'Custom-2: value 2',
        ]);

        $requestHandler->execute($request);
    }

    /**
     * @covers ::setSdkIdentifier
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage 'Invalid' is not in the list of accepted identifiers: PHP, WordPress, Drupal, Joomla
     */
    public function testInvalidSdkIdentifier()
    {
        $requestHandler = $this->createRequestHandler([
          '/',
          file_get_contents(PEM_FILE),
          SDK_ID,
          'Invalid'
        ]);

        $requestHandler->sendRequest('/', 'GET');
    }

    /**
     * Create mock for abstract request handler.
     *
     * @param array $constructorArgs
     *
     * @return \Yoti\Http\AbstractRequestHandler
     */
    private function createRequestHandler($constructorArgs)
    {
        return $this->getMockBuilder(AbstractRequestHandler::class)
          ->setConstructorArgs($constructorArgs)
          ->setMethods(['executeRequest'])
          ->getMockForAbstractClass();
    }

    /**
     * @covers ::sendRequest
     */
    public function testExecute()
    {
        $expectedUrl = self::BASE_URL;
        $expectedPayload = $this->createMock(Payload::class);
        $expectedMethod = 'GET';

        // Process associative array of headers into array of strings
        // formatted `Some-Header: some-value` as executeRequest() expects.
        $expectedHeaders = [
          'Custom' => 'value 1',
          'Custom-2' => 'value 2',
        ];
        $expectedHeaderArray = [];
        foreach ($expectedHeaders as $name => $value) {
            $expectedHeaderArray[] = "{$name}: {$value}";
        }

        $request = $this->getMockBuilder(Request::class)
          ->disableOriginalConstructor()
          ->setMethods([
            'getHeaders',
            'getUrl',
            'getMethod',
            'getPayload',
          ])
          ->getMock();

        $request->method('getUrl')->willReturn($expectedUrl);
        $request->method('getMethod')->willReturn($expectedMethod);
        $request->method('getPayload')->willReturn($expectedPayload);
        $request->method('getHeaders')->willReturn($expectedHeaders);

        $requestHandler = $this->createRequestHandler([
          '/',
          file_get_contents(PEM_FILE),
          SDK_ID,
        ]);

        $requestHandler->expects($this->exactly(1))
          ->method('executeRequest')
          ->with(
              $expectedHeaderArray,
              $expectedUrl,
              $expectedMethod,
              $expectedPayload
          );

        $requestHandler->execute($request);
    }

    /**
     * Asserts that the provided headers are correct.
     *
     * @param array $headers
     * @return bool
     */
    private function expectCorrectHeaders($requestHandler, $expectedHeaders)
    {
        $requestHandler->expects($this->exactly(1))
          ->method('executeRequest')
          ->with($this->callback(function ($headers) use ($expectedHeaders) {
            foreach ($expectedHeaders as $expectedHeader) {
                $this->assertContainsHeader($expectedHeader, $headers);
            }
            return true;
          }));
    }

    /**
     * Assert headers array contains provided expected header.
     *
     * @param string $expectedHeader
     * @param array $headers
     */
    private function assertContainsHeader($expectedHeader, $headers)
    {
        parent::assertContains($expectedHeader, $headers, print_r($headers, true));
    }
}
