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
     * @dataProvider lowPrecisionProvider
     *
     * On PHP 8.4 FPM environments (e.g. WP Engine) the `precision` ini setting
     * can cause (string)(round(microtime(true) * 1000)) to emit scientific notation
     * (e.g. "1.78231576830E+12"), which breaks Yoti signature verification with 401.
     */
    public function shouldReturnTimestampAsPlainIntegerStringUnderLowPrecision(string $precision)
    {
        $strategy = new SignedRequestStrategy(PemFile::fromFilePath(TestData::PEM_FILE));

        $originalPrecision = ini_get('precision');
        if ($originalPrecision === false || ini_set('precision', $precision) === false) {
            $this->markTestSkipped('Unable to set ini precision for this runtime');
        }

        try {
            $params = $strategy->createQueryParams();
        } finally {
            ini_set('precision', (string) $originalPrecision);
        }

        $this->assertMatchesRegularExpression(
            '/^\d+$/',
            $params['timestamp'],
            "Timestamp must be a plain integer string (no scientific notation) at precision={$precision}"
        );
    }

    public function lowPrecisionProvider(): array
    {
        return [
            'precision=12' => ['12'],
            'precision=8'  => ['8'],
        ];
    }

    /**
     * @test
     * @covers ::createQueryParams
     */
    public function shouldReturnTimestampAsUnixMilliseconds()
    {
        $strategy = new SignedRequestStrategy(PemFile::fromFilePath(TestData::PEM_FILE));

        $originalPrecision = ini_get('precision');
        if ($originalPrecision === false || ini_set('precision', '8') === false) {
            $this->markTestSkipped('Unable to set ini precision for this runtime');
        }

        try {
            $beforeMs = (int) floor(microtime(true) * 1000);
            $params = $strategy->createQueryParams();
            $afterMs = (int) ceil(microtime(true) * 1000);
        } finally {
            ini_set('precision', (string) $originalPrecision);
        }

        // Assert plain integer format first — (int) cast alone would mask scientific notation
        $this->assertMatchesRegularExpression('/^\d+$/', $params['timestamp']);
        $this->assertGreaterThanOrEqual($beforeMs, (int) $params['timestamp']);
        $this->assertLessThanOrEqual($afterMs, (int) $params['timestamp']);
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
