<?php

namespace YotiTest\Http;

use YotiTest\TestCase;
use Yoti\Http\AbstractRequestHandler;
use Yoti\Http\RequestBuilder;

/**
 * @coversDefaultClass \Yoti\Http\RequestBuilder
 */
class RequestBuilderTest extends TestCase
{
    /**
     * Test Base URL.
     */
    const BASE_URL = 'http://www.example.com/api/v1';

    /**
     * @covers ::build
     */
    public function testBuild()
    {
        $requestHandler = (new RequestBuilder)
          ->withBaseUrl(self::BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withSdkIdentifier('PHP')
          ->withSdkVersion('1.2.3')
          ->build();

        $this->assertInstanceOf(AbstractRequestHandler::class, $requestHandler);
    }

    /**
     * @covers ::build
     * @covers ::withSdkIdentifier
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage 'Invalid' is not in the list of accepted identifiers: PHP, WordPress, Drupal, Joomla
     */
    public function testBuildWithInvalidSdkIdentifier()
    {
        (new RequestBuilder)
          ->withBaseUrl(self::BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withSdkIdentifier('Invalid')
          ->build();
    }

    /**
     * @covers ::build
     * @covers ::withSdkVersion
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage Yoti SDK version must be a string
     */
    public function testBuildWithInvalidSdkVersion()
    {
        (new RequestBuilder)
          ->withBaseUrl(self::BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withSdkVersion(array('Invalid SDK Version'))
          ->build();
    }

    /**
     * @covers ::build
     * @covers ::withPemFilePath
     */
    public function testBuildWithPemFromFilePath()
    {
        $requestHandler = (new RequestBuilder)
          ->withBaseUrl(self::BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->build();

        $this->assertInstanceOf(AbstractRequestHandler::class, $requestHandler);
    }

    /**
     * @covers ::build
     * @covers ::withPemString
     */
    public function testBuildWithPemString()
    {
        $requestHandler = (new RequestBuilder)
          ->withBaseUrl(self::BASE_URL)
          ->withPemString(file_get_contents(PEM_FILE))
          ->build();

        $this->assertInstanceOf(AbstractRequestHandler::class, $requestHandler);
    }

    /**
     * @covers ::build
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage Base URL must be provided to Yoti\Http\RequestBuilder
     */
    public function testBuildWithoutBaseUrl()
    {
        (new RequestBuilder)
          ->withPemFilePath(PEM_FILE)
          ->build();
    }

    /**
     * @covers ::build
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage Pem file must be provided to Yoti\Http\RequestBuilder
     */
    public function testBuildWithoutPem()
    {
        (new RequestBuilder)
            ->withBaseUrl(self::BASE_URL)
            ->build();
    }
}
