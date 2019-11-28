<?php

namespace YotiTest\Http;

use Yoti\Http\AbstractRequestHandler;
use Yoti\Http\Payload;
use YotiTest\TestCase;
use Yoti\Util\Constants;

/**
 * @coversDefaultClass \Yoti\Http\AbstractRequestHandler
 */
class AbstractRequestHandlerTest extends TestCase
{
    /**
     * Test Base URL.
     */
    const SOME_BASE_URL = 'http://www.example.com/api/v1';

    /**
     * Test endpoint.
     */
    const SOME_ENDPOINT = '/some-endpoint';

    /**
     * @covers ::__construct
     * @covers ::sendRequest
     * @covers ::executeRequest
     */
    public function testSendRequest()
    {
        $requestHandler = $this->createRequestHandler([
          self::SOME_BASE_URL,
          file_get_contents(PEM_FILE),
          SDK_ID,
        ]);

        $version = Constants::SDK_VERSION;

        $expectedPayload = $this->createMock(Payload::class);
        $expectedUrl = self::SOME_BASE_URL . self::SOME_ENDPOINT;
        $expectedMethod = 'GET';
        $expectedHeaders = [
          "X-Yoti-SDK-Version: PHP-{$version}",
          'X-Yoti-SDK: PHP',
          'Content-Type: application/json',
          'Accept: application/json',
        ];

        $this->expectExecuteRequestWith(
            $requestHandler,
            $expectedHeaders,
            $expectedUrl,
            $expectedMethod,
            $expectedPayload
        );

        $requestHandler->sendRequest(self::SOME_ENDPOINT, $expectedMethod, $expectedPayload);
    }

    /**
     * @covers ::__construct
     * @covers ::sendRequest
     * @covers ::executeRequest
     */
    public function testCustomSdkIdentifierConstructor()
    {
        $requestHandler = $this->createRequestHandler([
          self::SOME_BASE_URL,
          file_get_contents(PEM_FILE),
          SDK_ID,
          'Drupal'
        ]);

        $version = Constants::SDK_VERSION;

        $expectedMethod = 'POST';
        $expectedHeaders = [
          "X-Yoti-SDK-Version: Drupal-{$version}",
          'X-Yoti-SDK: Drupal',
          'Accept: application/json',
        ];

        $this->expectExecuteRequestWith(
            $requestHandler,
            $expectedHeaders,
            self::SOME_BASE_URL,
            $expectedMethod,
            null
        );

        $requestHandler->sendRequest(self::SOME_ENDPOINT, $expectedMethod);
    }

    /**
     * @covers ::__construct
     * @covers \Yoti\Http\RequestBuilder::withSdkIdentifier
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage 'Invalid' is not in the list of accepted identifiers: PHP, WordPress, Drupal, Joomla
     */
    public function testInvalidSdkIdentifier()
    {
        $requestHandler = $this->createRequestHandler([
          self::SOME_BASE_URL,
          file_get_contents(PEM_FILE),
          SDK_ID,
          'Invalid'
        ]);

        $requestHandler->sendRequest(self::SOME_ENDPOINT, 'GET');
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
     * Asserts that the provided headers are correct.
     *
     * @param array $headers
     * @return bool
     */
    private function expectExecuteRequestWith(
        $requestHandler,
        $expectedHeaders,
        $expectedUrl,
        $expectedMethod,
        $expectedPayload
    ) {
        $requestHandler->expects($this->exactly(1))
          ->method('executeRequest')
          ->with(
              $this->callback(function ($headers) use ($expectedHeaders) {
                foreach ($expectedHeaders as $expectedHeader) {
                    $this->assertContainsHeader($expectedHeader, $headers);
                }
                return true;
              }),
              $this->callback(function ($requestUrl) use ($expectedUrl) {
                $this->assertRegExp(
                    '/' . preg_quote($expectedUrl, '/') . '?.*?appId=.*?&nonce.*?timestamp=.*?/',
                    $requestUrl
                );
                return true;
              }),
              $expectedMethod,
              $expectedPayload
          );
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
