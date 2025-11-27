<?php

declare(strict_types=1);

namespace Yoti\Test\Http\Auth;

use Yoti\Http\Auth\SignedRequestAuthStrategy;
use Yoti\Http\Payload;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\PemFile;

/**
 * @coversDefaultClass \Yoti\Http\Auth\SignedRequestAuthStrategy
 */
class SignedRequestAuthStrategyTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::applyAuth
     */
    public function testApplyAuthAddsDigestHeader()
    {
        $pemFile = PemFile::fromFilePath(TestData::PEM_FILE);
        $strategy = new SignedRequestAuthStrategy($pemFile);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $endpoint = '/some-endpoint?nonce=abc123&timestamp=1234567890';
        $httpMethod = 'POST';
        $payload = Payload::fromString('test payload');

        $result = $strategy->applyAuth($headers, $endpoint, $httpMethod, $payload);

        $this->assertArrayHasKey('X-Yoti-Auth-Digest', $result);
        $this->assertNotEmpty($result['X-Yoti-Auth-Digest']);
        $this->assertEquals('application/json', $result['Content-Type']);
    }

    /**
     * @covers ::applyAuth
     */
    public function testApplyAuthWithoutPayload()
    {
        $pemFile = PemFile::fromFilePath(TestData::PEM_FILE);
        $strategy = new SignedRequestAuthStrategy($pemFile);

        $headers = [
            'Accept' => 'application/json',
        ];

        $endpoint = '/some-endpoint?nonce=abc123&timestamp=1234567890';
        $httpMethod = 'GET';

        $result = $strategy->applyAuth($headers, $endpoint, $httpMethod, null);

        $this->assertArrayHasKey('X-Yoti-Auth-Digest', $result);
        $this->assertNotEmpty($result['X-Yoti-Auth-Digest']);
        $this->assertEquals('application/json', $result['Accept']);
    }
}
