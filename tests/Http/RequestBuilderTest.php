<?php

namespace YotiTest\Http;

use Psr\Http\Client\ClientInterface;
use YotiTest\TestCase;
use Yoti\Http\Request;
use Yoti\Http\RequestBuilder;
use Yoti\Http\Payload;

/**
 * @coversDefaultClass \Yoti\Http\RequestBuilder
 */
class RequestBuilderTest extends TestCase
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
     * @covers ::build
     * @covers ::withBaseUrl
     * @covers ::withPemFilePath
     * @covers ::withMethod
     * @covers ::withEndpoint
     * @covers ::withSdkIdentifier
     * @covers ::withSdkVersion
     * @covers ::getHeaders
     * @covers ::validateMethod
     * @covers ::validateHeaders
     * @covers \Yoti\Http\Request::__construct
     * @covers \Yoti\Http\Request::getMessage
     */
    public function testBuild()
    {
        $expectedPayload = new Payload('SOME PAYLOAD');

        $request = (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withMethod('POST')
          ->withEndpoint('/some-endpoint')
          ->withSdkIdentifier('PHP')
          ->withSdkVersion('1.2.3')
          ->withPayload($expectedPayload)
          ->build();

        $this->assertInstanceOf(Request::class, $request);

        $baseUrlPattern = preg_quote(self::SOME_BASE_URL, '~');
        $expectedEndpointPattern = "~{$baseUrlPattern}/some-endpoint.*?nonce=.*?&timestamp=.*?~";

        $message = $request->getMessage();
        $this->assertRegExp($expectedEndpointPattern, $message->getUri());
        $this->assertEquals('POST', $message->getMethod());
        $this->assertEquals('PHP', $message->getHeader('X-Yoti-SDK')[0]);
        $this->assertEquals('PHP-1.2.3', $message->getHeader('X-Yoti-SDK-Version')[0]);
        $this->assertNotEmpty($message->getHeader('X-Yoti-Auth-Digest')[0]);
        $this->assertEquals('application/json', $message->getHeader('Content-Type')[0]);
        $this->assertEquals('application/json', $message->getHeader('Accept')[0]);
        $this->assertEquals($expectedPayload->getPayloadJSON(), $message->getBody());
    }

    /**
     * @covers ::build
     * @covers ::withSdkIdentifier
     * @covers ::withSdkVersion
     */
    public function testWithSdkIdentifier()
    {
        $request = (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withGet()
          ->withSdkIdentifier('Drupal')
          ->withSdkVersion('4.5.6')
          ->build();

        $message = $request->getMessage();
        $this->assertEquals('Drupal', $message->getHeader('X-Yoti-SDK')[0]);
        $this->assertEquals('Drupal-4.5.6', $message->getHeader('X-Yoti-SDK-Version')[0]);
    }

    /**
     * @covers ::build
     * @covers ::getHeaders
     */
    public function testWithoutSdkIdentifier()
    {
        $request = (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withGet()
          ->build();

        $message = $request->getMessage();
        $this->assertEquals('PHP', $message->getHeader('X-Yoti-SDK')[0]);
        $this->assertRegExp('~PHP-\d+.\d+.\d+~', $message->getHeader('X-Yoti-SDK-Version')[0]);
    }

    /**
     * @covers ::build
     * @covers ::withPost
     */
    public function testWithPost()
    {
        $request = (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withPost()
          ->build();

        $message = $request->getMessage();
        $this->assertEquals('POST', $message->getMethod());
    }

    /**
     * @covers ::build
     * @covers ::withGet
     */
    public function testWithGet()
    {
        $request = (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withGet()
          ->build();

        $message = $request->getMessage();
        $this->assertEquals('GET', $message->getMethod());
        $this->assertArrayNotHasKey('Content-Type', $message->getHeaders());
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
          ->withBaseUrl(self::SOME_BASE_URL)
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
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withSdkVersion(['Invalid SDK Version'])
          ->build();
    }

    /**
     * @covers ::build
     * @covers ::withPemFilePath
     * @covers ::withPemFile
     */
    public function testBuildWithPemFromFilePath()
    {
        $request = (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withMethod('GET')
          ->build();

        $this->assertInstanceOf(Request::class, $request);
    }

    /**
     * @covers ::build
     * @covers ::withClient
     */
    public function testWithClient()
    {
        $client = $this->createMock(ClientInterface::class);

        $request = (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withEndpoint('/some-endpoint')
          ->withPemFilePath(PEM_FILE)
          ->withMethod('GET')
          ->withClient($client)
          ->build();

        $client->expects($this->exactly(1))
          ->method('sendRequest')
          ->with($request->getMessage());

        $request->execute();
    }

    /**
     * @covers ::build
     * @covers ::withPemString
     * @covers ::withPemFile
     */
    public function testBuildWithPemString()
    {
        $request = (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
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
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withHeader('Custom', 'custom header value')
          ->withHeader('Custom-2', 'a second custom header value')
          ->withMethod('GET')
          ->build();

        $this->assertInstanceOf(Request::class, $request);

        $message = $request->getMessage();
        $this->assertEquals('custom header value', $message->getHeader('Custom')[0]);
        $this->assertEquals('a second custom header value', $message->getHeader('Custom-2')[0]);
    }

    /**
     * @covers ::build
     * @covers ::withPayload
     */
    public function testWithPayload()
    {
        $expectedPayload = new Payload('some content');

        $request = (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withPayload($expectedPayload)
          ->withPost()
          ->build();

        $this->assertSame($expectedPayload->getPayloadJSON(), (string) $request->getMessage()->getBody());
    }

    /**
     * @covers ::build
     * @covers ::validateMethod
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage HTTP Method must be specified
     */
    public function testWithoutMethod()
    {
        (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->build();
    }

    /**
     * @covers ::build
     * @covers ::withMethod
     * @covers ::validateMethod
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage Unsupported HTTP Method SOME_METHOD
     */
    public function testWithUnsupportedMethod()
    {
        (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withPemFilePath(PEM_FILE)
          ->withMethod('SOME_METHOD')
          ->build();
    }

    /**
     * @covers ::build
     * @covers ::withHeader
     * @covers ::validateHeaders
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage Header value for 'Custom' must be a string
     */
    public function testWithHeaderInvalidValue()
    {
        (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
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
            ->withBaseUrl(self::SOME_BASE_URL)
            ->build();
    }

    /**
     * @covers ::build
     * @covers ::withBaseUrl
     */
    public function testWithBaseUrlTrailingSlashes()
    {
        $trailingSlashes = '/////';

        $request = (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL . $trailingSlashes)
          ->withEndpoint(self::SOME_ENDPOINT)
          ->withPemFilePath(PEM_FILE)
          ->withMethod('GET')
          ->build();

        $this->assertContains(self::SOME_BASE_URL . self::SOME_ENDPOINT, (string) $request->getMessage()->getUri());
    }

    /**
     * @covers ::build
     * @covers ::withEndpoint
     */
    public function testWithEndpointMultipleLeadingSlashes()
    {
        $endpointLeadingSlashes = '/////' . self::SOME_ENDPOINT;

        $request = (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withEndpoint($endpointLeadingSlashes)
          ->withPemFilePath(PEM_FILE)
          ->withMethod('GET')
          ->build();

        $this->assertContains(self::SOME_BASE_URL . self::SOME_ENDPOINT, (string) $request->getMessage()->getUri());
    }

    /**
     * @covers ::build
     * @covers ::withEndpoint
     */
    public function testWithEndpointNoLeadingSlashes()
    {
        $endpointNoSlashes = ltrim(self::SOME_ENDPOINT, '/');

        $request = (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withEndpoint($endpointNoSlashes)
          ->withPemFilePath(PEM_FILE)
          ->withMethod('GET')
          ->build();

        $this->assertContains(self::SOME_BASE_URL . self::SOME_ENDPOINT, (string) $request->getMessage()->getUri());
    }

    /**
     * @covers ::build
     * @covers ::withQueryParam
     */
    public function testWithQueryParam()
    {
        $expectedQueryParams = [
          'some' => 'value 1',
          'another' => 'value 2',
        ];
        $requestBuilder = (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withEndpoint(self::SOME_ENDPOINT)
          ->withPemFilePath(PEM_FILE)
          ->withGet();
        foreach ($expectedQueryParams as $key => $value) {
            $requestBuilder->withQueryParam($key, $value);
        }
        $request = $requestBuilder->build();

        parse_str(parse_url($request->getMessage()->getUri(), PHP_URL_QUERY), $queryParams);

        foreach ($expectedQueryParams as $key => $value) {
            $this->assertEquals($expectedQueryParams[$key], $queryParams[$key]);
        }

        $this->assertNotNull($queryParams['nonce']);
        $this->assertNotNull($queryParams['timestamp']);
    }
}
