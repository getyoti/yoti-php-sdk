<?php

declare(strict_types=1);

namespace Yoti\Test\Http\AuthStrategy;

use Yoti\Http\AuthStrategy\NoAuthStrategy;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Http\AuthStrategy\NoAuthStrategy
 */
class NoAuthStrategyTest extends TestCase
{
    /**
     * @test
     * @covers ::createAuthHeaders
     */
    public function shouldReturnEmptyHeaders()
    {
        $strategy = new NoAuthStrategy();
        $headers = $strategy->createAuthHeaders('GET', '/endpoint', null);

        $this->assertIsArray($headers);
        $this->assertEmpty($headers);
    }

    /**
     * @test
     * @covers ::createQueryParams
     */
    public function shouldReturnEmptyQueryParams()
    {
        $strategy = new NoAuthStrategy();
        $params = $strategy->createQueryParams();

        $this->assertIsArray($params);
        $this->assertEmpty($params);
    }
}
