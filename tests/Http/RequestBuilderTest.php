<?php

namespace YotiTest\Http;

use YotiTest\TestCase;
use Yoti\Http\Request;
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
        $request = (new RequestBuilder())
          ->withBaseUrl(self::BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withMethod('POST')
          ->withEndpoint('/some-endpoint')
          ->withSdkIdentifier('PHP')
          ->withSdkVersion('1.2.3')
          ->build();

        $this->assertInstanceOf(Request::class, $request);

        $expectedEndpointPattern = '~' . preg_quote(self::BASE_URL, '~') . '/some-endpoint.*?nonce=.*?&timestamp=.*?~';

        $this->assertRegExp($expectedEndpointPattern, $request->getUrl());
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('PHP', $request->getHeaders()['X-Yoti-SDK']);
        $this->assertEquals('PHP-1.2.3', $request->getHeaders()['X-Yoti-SDK-Version']);
        $this->assertEquals('application/json', $request->getHeaders()['Content-Type']);
        $this->assertEquals('application/json', $request->getHeaders()['Accept']);
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
        (new RequestBuilder())
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
        (new RequestBuilder())
          ->withBaseUrl(self::BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withSdkVersion(['Invalid SDK Version'])
          ->build();
    }

    /**
     * @covers ::build
     * @covers ::withPemFilePath
     */
    public function testBuildWithPemFromFilePath()
    {
        $request = (new RequestBuilder())
          ->withBaseUrl(self::BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withMethod('GET')
          ->build();

        $this->assertInstanceOf(Request::class, $request);
    }

    /**
     * @covers ::build
     * @covers ::withPemString
     */
    public function testBuildWithPemString()
    {
        $request = (new RequestBuilder())
          ->withBaseUrl(self::BASE_URL)
          ->withPemString(file_get_contents(PEM_FILE))
          ->withMethod('GET')
          ->build();

        $this->assertInstanceOf(Request::class, $request);
    }

    /**
     * @covers ::build
     * @covers ::withHeader
     */
    public function testBuildWithHeader()
    {
        $request = (new RequestBuilder())
          ->withBaseUrl(self::BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withHeader('Custom', 'custom header value')
          ->withHeader('Custom-2', 'a second custom header value')
          ->withMethod('GET')
          ->build();

        $this->assertInstanceOf(Request::class, $request);
        $this->assertEquals('custom header value', $request->getHeaders()['Custom']);
        $this->assertEquals('a second custom header value', $request->getHeaders()['Custom-2']);
    }

    /**
     * @covers ::build
     * @covers ::withHeader
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage Header value for 'Custom' must be a string
     */
    public function testWithHeaderInvalidValue()
    {
        (new RequestBuilder())
          ->withBaseUrl(self::BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withHeader('Custom', ['invalid value'])
          ->withMethod('GET')
          ->build();
    }

    /**
     * @covers ::build
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage Base URL must be provided to Yoti\Http\RequestBuilder
     */
    public function testBuildWithoutBaseUrl()
    {
        (new RequestBuilder())
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
        (new RequestBuilder())
            ->withBaseUrl(self::BASE_URL)
            ->build();
    }
}
