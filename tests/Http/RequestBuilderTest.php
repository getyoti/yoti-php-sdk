<?php

namespace YotiTest\Http;

use YotiTest\TestCase;
use Yoti\Http\Request;
use Yoti\Http\RequestBuilder;
use Yoti\Http\Payload;
use Yoti\Http\RequestHandlerInterface;

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
     * @covers ::withBaseUrl
     * @covers ::withPemFilePath
     * @covers ::withMethod
     * @covers ::withEndpoint
     * @covers ::withSdkIdentifier
     * @covers ::withSdkVersion
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
        $this->assertEquals(PEM_AUTH_KEY, $request->getHeaders()['X-Yoti-Auth-Key']);
        $this->assertNotEmpty($request->getHeaders()['X-Yoti-Auth-Digest']);
        $this->assertEquals('application/json', $request->getHeaders()['Content-Type']);
        $this->assertEquals('application/json', $request->getHeaders()['Accept']);
    }

    /**
     * @covers ::build
     * @covers ::withSdkIdentifier
     * @covers ::withSdkVersion
     */
    public function testWithSdkIdentifier()
    {
        $request = (new RequestBuilder())
          ->withBaseUrl(self::BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withGet()
          ->withSdkIdentifier('Drupal')
          ->withSdkVersion('4.5.6')
          ->build();

        $this->assertEquals('Drupal', $request->getHeaders()['X-Yoti-SDK']);
        $this->assertEquals('Drupal-4.5.6', $request->getHeaders()['X-Yoti-SDK-Version']);
    }

    /**
     * @covers ::build
     * @covers ::withPost
     */
    public function testWithPost()
    {
        $request = (new RequestBuilder())
          ->withBaseUrl(self::BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withPost()
          ->build();

        $this->assertEquals('POST', $request->getMethod());
    }

    /**
     * @covers ::build
     * @covers ::withGet
     */
    public function testWithGet()
    {
        $request = (new RequestBuilder())
          ->withBaseUrl(self::BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withGet()
          ->build();

        $this->assertEquals('GET', $request->getMethod());
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
     * @covers ::withHandler
     */
    public function testWithHandler()
    {
        $handler = $this->getMockBuilder(RequestHandlerInterface::class)
          ->disableOriginalConstructor()
          ->setMethods(['execute'])
          ->getMockForAbstractClass();

        $request = (new RequestBuilder())
          ->withBaseUrl(self::BASE_URL)
          ->withEndpoint('/some-endpoint')
          ->withPemFilePath(PEM_FILE)
          ->withMethod('GET')
          ->withHandler($handler)
          ->build();

        $handler->expects($this->exactly(1))
          ->method('execute')
          ->with($request);

        $request->execute();
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
     * @covers ::withPayload
     */
    public function testWithPayload()
    {
        $expectedPayload = new Payload('some content');

        $request = (new RequestBuilder())
          ->withBaseUrl(self::BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withPayload($expectedPayload)
          ->withPost()
          ->build();

        $this->assertSame($expectedPayload, $request->getPayload());
    }

    /**
     * @covers ::build
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage HTTP Method must be specified
     */
    public function testWithoutMethod()
    {
        (new RequestBuilder())
          ->withBaseUrl(self::BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->build();
    }

    /**
     * @covers ::build
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage Unsupported HTTP Method SOME_METHOD
     */
    public function testWithUnsupportedMethod()
    {
        (new RequestBuilder())
          ->withBaseUrl(self::BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withMethod('SOME_METHOD')
          ->build();
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
