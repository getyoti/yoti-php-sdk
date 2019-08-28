<?php

namespace YotiTest\Http;

use Yoti\Http\AbstractRequestHandler;
use YotiTest\TestCase;
use Yoti\Util\Config;
use Yoti\Util\PemFile;

/**
 * @coversDefaultClass \Yoti\Http\AbstractRequestHandler
 */
class AbstractRequestHandlerTest extends TestCase
{
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

        return $this->assertCorrectHeaders($requestHandler, [
          "X-Yoti-SDK-Version: PHP-{$version}",
          'X-Yoti-SDK: PHP',
        ]);
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

        $this->assertCorrectHeaders($requestHandler, [
          "X-Yoti-SDK-Version: Drupal-2.2.1",
          'X-Yoti-SDK: Drupal',
        ]);
    }

    /**
     * @covers ::sendRequest
     * @covers ::executeRequest
     */
    public function testCustomSdkHeaders()
    {
        $requestHandler = $this->createRequestHandler([
          '/',
          file_get_contents(PEM_FILE),
          SDK_ID
        ]);

        $requestHandler->setSdkIdentifier('WordPress');
        $requestHandler->setSdkVersion('1.2.3');

        $this->assertCorrectHeaders($requestHandler, [
          "X-Yoti-SDK-Version: WordPress-1.2.3",
          'X-Yoti-SDK: WordPress',
        ]);
    }

    /**
     * @covers ::setSdkIdentifier
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage 'Invalid' is not in the list of accepted identifiers: PHP, WordPress, Drupal, Joomla
     */
    public function testInvalidSdkIdentifier()
    {
        $requestHandler = $this->getMockBuilder(AbstractRequestHandler::class)
          ->disableOriginalConstructor()
          ->getMockForAbstractClass();

        $requestHandler->setSdkIdentifier('Invalid');
    }

    /**
     * @covers ::setSdkVersion
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage Yoti SDK version must be a string
     */
    public function testInvalidSdkVersion()
    {
        $requestHandler = $this->getMockBuilder(AbstractRequestHandler::class)
          ->disableOriginalConstructor()
          ->getMockForAbstractClass();

        $requestHandler->setSdkVersion(['invalid version']);
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
    private function assertCorrectHeaders($requestHandler, $expectedHeaders)
    {
        $requestHandler->expects($this->exactly(1))
          ->method('executeRequest')
          ->with($this->callback(function ($headers) use ($expectedHeaders) {
            foreach ($expectedHeaders as $expectedHeader) {
                $this->assertContainsHeader($expectedHeader, $headers);
            }
            $authKey = PEM_AUTH_KEY;
            $this->assertContainsHeader("X-Yoti-Auth-Key: {$authKey}", $headers);
            $this->assertContainsHeader('Content-Type: application/json', $headers);
            $this->assertContainsHeader('Accept: application/json', $headers);
            return true;
          }));

        $requestHandler->sendRequest('/', 'GET');
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
