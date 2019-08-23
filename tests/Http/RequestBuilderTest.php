<?php

namespace YotiTest\Http;

use YotiTest\TestCase;
use Yoti\Http\AbstractRequestHandler;
use Yoti\Http\RequestBuilder;
use Yoti\Util\PemFile;

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
     * @var Yoti\Util\PemFile
     */
    private $pemFile;

    /**
     * Setup tests.
     */
    public function setup()
    {
        $this->pemFile = $this->getMockBuilder(PemFile::class)
            ->disableOriginalConstructor()
            ->setMethods(['__toString'])
            ->getMock();

        $this->pemFile->method('__toString')
            ->willReturn(file_get_contents(PEM_FILE));
    }

    /**
     * @covers ::build
     */
    public function testBuild()
    {
        $requestHandler = (new RequestBuilder)
          ->withBaseUrl(self::BASE_URL)
          ->withPemFile($this->pemFile)
          ->build();

        $this->assertInstanceOf(AbstractRequestHandler::class, $requestHandler);
    }

    /**
     * @covers ::build
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
     */
    public function testBuildWithPemFromString()
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
          ->withPemFile($this->pemFile)
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
