<?php

namespace YotiTest\Http;

use Yoti\Http\AbstractRequestHandler;
use YotiTest\TestCase;
use Yoti\Util\Config;

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
        $requestHandler = $this->getMockBuilder(AbstractRequestHandler::class)
          ->setConstructorArgs(['/', file_get_contents(PEM_FILE), SDK_ID, 'PHP'])
          ->setMethods(['executeRequest'])
          ->getMockForAbstractClass();

        $requestHandler->expects($this->exactly(1))
          ->method('executeRequest')
          ->with($this->callback(function ($headers) {
            return $this->assertCorrectHeaders($headers);
          }));

        $requestHandler->sendRequest('/', 'GET');
    }

    /**
     * Asserts that the provided headers are correct.
     *
     * @param array $headers
     * @return bool
     */
    private function assertCorrectHeaders($headers)
    {
        $version = Config::getInstance()->get('version');
        $this->assertContains("X-Yoti-SDK-Version: PHP-{$version}", $headers);
        $this->assertContains('X-Yoti-SDK: PHP', $headers);
        $this->assertContains('Content-Type: application/json', $headers);
        $this->assertContains('Accept: application/json', $headers);

        return true;
    }
}
