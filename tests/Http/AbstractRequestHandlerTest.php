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
    public function testCustomSdkHeaders()
    {
        $requestHandler = $this->createRequestHandler([
          '/',
          file_get_contents(PEM_FILE),
          SDK_ID,
          'WordPress',
          '1.2.3'
        ]);

        $this->assertCorrectHeaders($requestHandler, [
          "X-Yoti-SDK-Version: WordPress-1.2.3",
          'X-Yoti-SDK: WordPress',
        ]);
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
            $authKey = PemFile::fromFilePath(PEM_FILE)->getAuthKey();
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
