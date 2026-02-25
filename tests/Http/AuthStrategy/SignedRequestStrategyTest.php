<?php

declare(strict_types=1);

namespace Yoti\Test\Http\AuthStrategy;

use Yoti\Http\AuthStrategy\SignedRequestStrategy;
use Yoti\Http\Payload;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\PemFile;

/**
 * @coversDefaultClass \Yoti\Http\AuthStrategy\SignedRequestStrategy
 */
class SignedRequestStrategyTest extends TestCase
{
    private const SOME_SDK_ID = 'some-sdk-id';
    private const SOME_HTTP_METHOD = 'GET';
    private const SOME_ENDPOINT = '/some/endpoint';

    /**
     * @test
     * @covers ::__construct
     * @covers ::createAuthHeaders
     */
    public function shouldReturnDigestHeader()
    {
        $pemFile = PemFile::fromFilePath(TestData::PEM_FILE);
        $strategy = new SignedRequestStrategy($pemFile);

        $headers = $strategy->createAuthHeaders(self::SOME_HTTP_METHOD, self::SOME_ENDPOINT, null);

        $this->assertArrayHasKey('X-Yoti-Auth-Digest', $headers);
        $this->assertNotEmpty($headers['X-Yoti-Auth-Digest']);
    }

    /**
     * @test
     * @covers ::createAuthHeaders
     */
    public function shouldReturnDigestHeaderWithPayload()
    {
        $pemFile = PemFile::fromFilePath(TestData::PEM_FILE);
        $strategy = new SignedRequestStrategy($pemFile);

        $payload = Payload::fromString('some payload content');
        $headers = $strategy->createAuthHeaders('POST', self::SOME_ENDPOINT, $payload);

        $this->assertArrayHasKey('X-Yoti-Auth-Digest', $headers);
        $this->assertNotEmpty($headers['X-Yoti-Auth-Digest']);
    }

    /**
     * @test
     * @covers ::createQueryParams
     */
    public function shouldReturnNonceAndTimestampQueryParams()
    {
        $pemFile = PemFile::fromFilePath(TestData::PEM_FILE);
        $strategy = new SignedRequestStrategy($pemFile);

        $params = $strategy->createQueryParams();

        $this->assertArrayHasKey('nonce', $params);
        $this->assertArrayHasKey('timestamp', $params);
        $this->assertNotEmpty($params['nonce']);
        $this->assertNotEmpty($params['timestamp']);
    }

    /**
     * @test
     * @covers ::createQueryParams
     */
    public function shouldIncludeNonceAsUuidFormat()
    {
        $pemFile = PemFile::fromFilePath(TestData::PEM_FILE);
        $strategy = new SignedRequestStrategy($pemFile);

        $params = $strategy->createQueryParams();

        // UUID v4 pattern: 8-4-4-4-12 hex chars
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/',
            $params['nonce']
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::createQueryParams
     */
    public function shouldIncludeSdkIdWhenProvided()
    {
        $pemFile = PemFile::fromFilePath(TestData::PEM_FILE);
        $strategy = new SignedRequestStrategy($pemFile, self::SOME_SDK_ID);

        $params = $strategy->createQueryParams();

        $this->assertArrayHasKey('sdkId', $params);
        $this->assertEquals(self::SOME_SDK_ID, $params['sdkId']);
    }

    /**
     * @test
     * @covers ::createQueryParams
     */
    public function shouldNotIncludeSdkIdWhenNotProvided()
    {
        $pemFile = PemFile::fromFilePath(TestData::PEM_FILE);
        $strategy = new SignedRequestStrategy($pemFile);

        $params = $strategy->createQueryParams();

        $this->assertArrayNotHasKey('sdkId', $params);
    }

    /**
     * @test
     * @covers ::createQueryParams
     */
    public function shouldReturnDifferentNonceEachTime()
    {
        $pemFile = PemFile::fromFilePath(TestData::PEM_FILE);
        $strategy = new SignedRequestStrategy($pemFile);

        $params1 = $strategy->createQueryParams();
        $params2 = $strategy->createQueryParams();

        $this->assertNotEquals($params1['nonce'], $params2['nonce']);
    }
}
