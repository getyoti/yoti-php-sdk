<?php

declare(strict_types=1);

namespace Yoti\Test\Http\Auth;

use Yoti\Http\Auth\TokenAuthStrategy;
use Yoti\Http\Payload;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Http\Auth\TokenAuthStrategy
 */
class TokenAuthStrategyTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::applyAuth
     */
    public function testApplyAuthAddsAuthorizationHeader()
    {
        $token = 'test-auth-token-12345';
        $strategy = new TokenAuthStrategy($token);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $endpoint = '/some-endpoint';
        $httpMethod = 'POST';
        $payload = Payload::fromString('test payload');

        $result = $strategy->applyAuth($headers, $endpoint, $httpMethod, $payload);

        $this->assertArrayHasKey('Authorization', $result);
        $this->assertEquals('Bearer test-auth-token-12345', $result['Authorization']);
        $this->assertEquals('application/json', $result['Content-Type']);
    }

    /**
     * @covers ::applyAuth
     */
    public function testApplyAuthWithoutPayload()
    {
        $token = 'another-token';
        $strategy = new TokenAuthStrategy($token);

        $headers = [
            'Accept' => 'application/json',
        ];

        $endpoint = '/some-endpoint';
        $httpMethod = 'GET';

        $result = $strategy->applyAuth($headers, $endpoint, $httpMethod, null);

        $this->assertArrayHasKey('Authorization', $result);
        $this->assertEquals('Bearer another-token', $result['Authorization']);
        $this->assertEquals('application/json', $result['Accept']);
    }
}
