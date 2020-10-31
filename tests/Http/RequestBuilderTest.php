<?php

declare(strict_types=1);

namespace Yoti\Test\Http;

use Psr\Http\Client\ClientInterface;
use Yoti\Http\Payload;
use Yoti\Http\Request;
use Yoti\Http\RequestBuilder;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\Config;

/**
 * @coversDefaultClass \Yoti\Http\RequestBuilder
 */
class RequestBuilderTest extends TestCase
{
    /**
     * Test Base URL.
     */
    private const SOME_BASE_URL = 'http://www.example.com/api/v1';

    /**
     * Test endpoint.
     */
    private const SOME_ENDPOINT = '/some-endpoint';

    /**
     * @covers ::build
     * @covers ::withBaseUrl
     * @covers ::withPemFilePath
     * @covers ::withMethod
     * @covers ::withEndpoint
     * @covers ::getHeaders
     * @covers ::validateMethod
     * @covers \Yoti\Http\Request::__construct
     * @covers \Yoti\Http\Request::getMessage
     */
    public function testBuild()
    {
        $expectedPayload = Payload::fromString('SOME PAYLOAD');

        $request = (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withPemFilePath(TestData::PEM_FILE)
          ->withMethod('POST')
          ->withEndpoint('/some-endpoint')
          ->withPayload($expectedPayload)
          ->build();

        $this->assertInstanceOf(Request::class, $request);

        $baseUrlPattern = preg_quote(self::SOME_BASE_URL, '~');
        $expectedEndpointPattern = "~{$baseUrlPattern}/some-endpoint.*?nonce=.*?&timestamp=.*?~";

        $message = $request->getMessage();
        $this->assertMatchesRegularExpression($expectedEndpointPattern, (string) $message->getUri());
        $this->assertEquals('POST', $message->getMethod());
        $this->assertEquals('PHP', $message->getHeader('X-Yoti-SDK')[0]);
        $this->assertMatchesRegularExpression('~PHP-\d+\.\d+\.\d+~', $message->getHeader('X-Yoti-SDK-Version')[0]);
        $this->assertNotEmpty($message->getHeader('X-Yoti-Auth-Digest')[0]);
        $this->assertEquals('application/json', $message->getHeader('Content-Type')[0]);
        $this->assertEquals('application/json', $message->getHeader('Accept')[0]);
        $this->assertEquals($expectedPayload->toStream(), $message->getBody());
    }

    /**
     * @covers ::build
     * @covers ::getHeaders
     */
    public function testCustomSdkIdentifier()
    {
        $config = new Config([
          Config::SDK_IDENTIFIER => 'Drupal',
          Config::SDK_VERSION => '4.5.6',
        ]);

        $request = (new RequestBuilder($config))
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withPemFilePath(TestData::PEM_FILE)
          ->withGet()
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
          ->withPemFilePath(TestData::PEM_FILE)
          ->withGet()
          ->build();

        $message = $request->getMessage();
        $this->assertEquals('PHP', $message->getHeader('X-Yoti-SDK')[0]);
        $this->assertMatchesRegularExpression('~PHP-\d+.\d+.\d+~', $message->getHeader('X-Yoti-SDK-Version')[0]);
    }

    /**
     * @covers ::build
     * @covers ::withPost
     */
    public function testWithPost()
    {
        $request = (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withPemFilePath(TestData::PEM_FILE)
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
          ->withPemFilePath(TestData::PEM_FILE)
          ->withGet()
          ->build();

        $message = $request->getMessage();
        $this->assertEquals('GET', $message->getMethod());
        $this->assertArrayNotHasKey('Content-Type', $message->getHeaders());
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
          ->withPemFilePath(TestData::PEM_FILE)
          ->withMethod('GET')
          ->build();

        $this->assertInstanceOf(Request::class, $request);
    }

    /**
     * @covers ::__construct
     * @covers ::build
     */
    public function testCustomHttpClient()
    {
        $client = $this->createMock(ClientInterface::class);
        $config = new Config([
          Config::HTTP_CLIENT => $client,
        ]);

        $request = (new RequestBuilder($config))
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withEndpoint('/some-endpoint')
          ->withPemFilePath(TestData::PEM_FILE)
          ->withMethod('GET')
          ->build();

        $client->expects($this->exactly(1))
          ->method('sendRequest')
          ->with($request->getMessage());

        $request->execute();
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
          ->withPemFilePath(TestData::PEM_FILE)
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
          ->withPemString(file_get_contents(TestData::PEM_FILE))
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
          ->withPemFilePath(TestData::PEM_FILE)
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
        $expectedPayload = Payload::fromString('some content');

        $request = (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withPemFilePath(TestData::PEM_FILE)
          ->withPayload($expectedPayload)
          ->withPost()
          ->build();

        $this->assertSame($expectedPayload->toStream(), $request->getMessage()->getBody());
    }

    /**
     * @covers ::build
     * @covers ::validateMethod
     */
    public function testWithoutMethod()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('HTTP Method must be specified');

        (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withPemFilePath(TestData::PEM_FILE)
          ->build();
    }

    /**
     * @covers ::build
     * @covers ::withMethod
     * @covers ::validateMethod
     */
    public function testWithUnsupportedMethod()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported HTTP Method SOME_METHOD');

        (new RequestBuilder())
          ->withBaseUrl(self::SOME_BASE_URL)
          ->withPemFilePath(TestData::PEM_FILE)
          ->withMethod('SOME_METHOD')
          ->build();
    }

    /**
     * @covers ::build
     * @covers ::withHeader
     */
    public function testWithHeaderInvalidValue()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(sprintf('%s::withHeader()', RequestBuilder::class));

        (new RequestBuilder())->withHeader('Custom', ['invalid value']);
    }

    /**
     * @covers ::build
     */
    public function testBuildWithoutBaseUrl()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Base URL must be provided to Yoti\\Http\\RequestBuilder');

        (new RequestBuilder())
          ->withPemFilePath(TestData::PEM_FILE)
          ->build();
    }

    /**
     * @covers ::build
     */
    public function testBuildWithoutPem()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Pem file must be provided to Yoti\\Http\\RequestBuilder');

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
          ->withPemFilePath(TestData::PEM_FILE)
          ->withMethod('GET')
          ->build();

        $this->assertStringContainsString(
            self::SOME_BASE_URL . self::SOME_ENDPOINT,
            (string) $request->getMessage()->getUri()
        );
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
          ->withPemFilePath(TestData::PEM_FILE)
          ->withMethod('GET')
          ->build();

        $this->assertStringContainsString(
            self::SOME_BASE_URL . self::SOME_ENDPOINT,
            (string) $request->getMessage()->getUri()
        );
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
          ->withPemFilePath(TestData::PEM_FILE)
          ->withMethod('GET')
          ->build();

        $this->assertStringContainsString(
            self::SOME_BASE_URL . self::SOME_ENDPOINT,
            (string) $request->getMessage()->getUri()
        );
    }

    /**
     * @covers ::build
     * @covers ::withQueryParam
     * @covers ::generateNonce
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
          ->withPemFilePath(TestData::PEM_FILE)
          ->withGet();
        foreach ($expectedQueryParams as $key => $value) {
            $requestBuilder->withQueryParam($key, $value);
        }
        $request = $requestBuilder->build();

        parse_str(parse_url((string) $request->getMessage()->getUri(), PHP_URL_QUERY), $queryParams);

        foreach ($expectedQueryParams as $key => $value) {
            $this->assertEquals($expectedQueryParams[$key], $queryParams[$key]);
        }

        $this->assertNotNull($queryParams['nonce']);
        $this->assertNotNull($queryParams['timestamp']);
    }
}
