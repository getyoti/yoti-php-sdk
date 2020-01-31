<?php

namespace Yoti\Test\DocScan\Session\Create\Check;

use Yoti\DocScan\Session\Create\Check\RequestedLivenessConfig;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Check\RequestedLivenessConfig
 */
class RequestedLivenessCheckConfigTest extends TestCase
{

    private const SOME_LIVENESS_TYPE = 'someLivenessType';
    private const SOME_MAX_RETRIES = 5;

    /**
     * @test
     * @covers ::__construct
     * @covers ::getLivenessType
     * @covers ::getMaxRetries
     */
    public function shouldHoldValuesCorrectly()
    {
        $result = new RequestedLivenessConfig(self::SOME_LIVENESS_TYPE, self::SOME_MAX_RETRIES);

        $this->assertEquals(self::SOME_LIVENESS_TYPE, $result->getLivenessType());
        $this->assertEquals(self::SOME_MAX_RETRIES, $result->getMaxRetries());
    }

    /**
     * @test
     * @covers ::jsonSerialize
     */
    public function shouldSerializeToJsonCorrectly()
    {
        $result = new RequestedLivenessConfig(self::SOME_LIVENESS_TYPE, self::SOME_MAX_RETRIES);

        $expected = [
            'liveness_type' => self::SOME_LIVENESS_TYPE,
            'max_retries' => self::SOME_MAX_RETRIES,
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }
}
