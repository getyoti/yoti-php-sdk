<?php

declare(strict_types=1);

namespace Yoti\Test\Http\AuthStrategy;

use Yoti\Http\AuthStrategy\BearerTokenStrategy;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Http\AuthStrategy\BearerTokenStrategy
 */
class BearerTokenStrategyTest extends TestCase
{
    private const SOME_TOKEN = 'some-bearer-token-value';
    private const SOME_HTTP_METHOD = 'GET';
    private const SOME_ENDPOINT = '/some/endpoint';

    /**
     * @test
     * @covers ::__construct
     * @covers ::createAuthHeaders
     */
    public function shouldReturnAuthorizationBearerHeader()
    {
        $strategy = new BearerTokenStrategy(self::SOME_TOKEN);
        $headers = $strategy->createAuthHeaders(self::SOME_HTTP_METHOD, self::SOME_ENDPOINT, null);

        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertEquals('Bearer ' . self::SOME_TOKEN, $headers['Authorization']);
    }

    /**
     * @test
     * @covers ::createQueryParams
     */
    public function shouldReturnEmptyQueryParams()
    {
        $strategy = new BearerTokenStrategy(self::SOME_TOKEN);
        $params = $strategy->createQueryParams();

        $this->assertIsArray($params);
        $this->assertEmpty($params);
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldThrowOnEmptyToken()
    {
        $this->expectException(\InvalidArgumentException::class);
        new BearerTokenStrategy('');
    }

    /**
     * @test
     * @covers ::createAuthHeaders
     */
    public function shouldReturnOnlyAuthorizationHeader()
    {
        $strategy = new BearerTokenStrategy(self::SOME_TOKEN);
        $headers = $strategy->createAuthHeaders('POST', '/endpoint', null);

        $this->assertCount(1, $headers);
        $this->assertArrayHasKey('Authorization', $headers);
    }
}
