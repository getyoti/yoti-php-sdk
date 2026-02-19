<?php

declare(strict_types=1);

namespace Yoti\Test\Auth;

use Yoti\Auth\CreateAuthenticationTokenResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Auth\CreateAuthenticationTokenResponse
 */
class CreateAuthenticationTokenResponseTest extends TestCase
{
    private const SOME_ACCESS_TOKEN = 'some-access-token';
    private const SOME_TOKEN_TYPE = 'Bearer';
    private const SOME_EXPIRES_IN = 3600;
    private const SOME_SCOPE = 'scope1 scope2';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getAccessToken
     * @covers ::getTokenType
     * @covers ::getExpiresIn
     * @covers ::getScope
     */
    public function shouldParseFullResponseData()
    {
        $response = new CreateAuthenticationTokenResponse([
            'access_token' => self::SOME_ACCESS_TOKEN,
            'token_type' => self::SOME_TOKEN_TYPE,
            'expires_in' => self::SOME_EXPIRES_IN,
            'scope' => self::SOME_SCOPE,
        ]);

        $this->assertEquals(self::SOME_ACCESS_TOKEN, $response->getAccessToken());
        $this->assertEquals(self::SOME_TOKEN_TYPE, $response->getTokenType());
        $this->assertEquals(self::SOME_EXPIRES_IN, $response->getExpiresIn());
        $this->assertEquals(self::SOME_SCOPE, $response->getScope());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getExpiresIn
     * @covers ::getScope
     */
    public function shouldHandleMissingOptionalFields()
    {
        $response = new CreateAuthenticationTokenResponse([
            'access_token' => self::SOME_ACCESS_TOKEN,
            'token_type' => self::SOME_TOKEN_TYPE,
        ]);

        $this->assertEquals(self::SOME_ACCESS_TOKEN, $response->getAccessToken());
        $this->assertEquals(self::SOME_TOKEN_TYPE, $response->getTokenType());
        $this->assertNull($response->getExpiresIn());
        $this->assertNull($response->getScope());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldHandleEmptyResponseData()
    {
        $response = new CreateAuthenticationTokenResponse([]);

        $this->assertEquals('', $response->getAccessToken());
        $this->assertEquals('', $response->getTokenType());
        $this->assertNull($response->getExpiresIn());
        $this->assertNull($response->getScope());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getExpiresIn
     */
    public function shouldCastExpiresInToInteger()
    {
        $response = new CreateAuthenticationTokenResponse([
            'access_token' => self::SOME_ACCESS_TOKEN,
            'token_type' => self::SOME_TOKEN_TYPE,
            'expires_in' => '7200',
        ]);

        $this->assertSame(7200, $response->getExpiresIn());
    }
}
