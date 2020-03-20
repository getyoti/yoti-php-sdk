<?php

namespace Yoti\Test\DocScan\Session\Create\Check;

use Yoti\DocScan\Session\Create\Check\RequestedLivenessCheck;
use Yoti\DocScan\Session\Create\Check\RequestedLivenessCheckBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Check\RequestedLivenessCheckBuilder
 */
class RequstedLivenessCheckBuilderTest extends TestCase
{

    private const SOME_LIVENESS_TYPE = 'someLivenessType';
    private const SOME_MAX_RETRIES = 3;

    /**
     * @test
     * @covers ::build
     * @covers ::forLivenessType
     * @covers ::withMaxRetries
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedLivenessCheck::__construct
     */
    public function shouldCorrectlyBuildRequestedLivenessCheck()
    {
        $result = (new RequestedLivenessCheckBuilder())
            ->forLivenessType(self::SOME_LIVENESS_TYPE)
            ->withMaxRetries(self::SOME_MAX_RETRIES)
            ->build();

        $this->assertInstanceOf(RequestedLivenessCheck::class, $result);
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedLivenessCheck::jsonSerialize
     * @covers ::forLivenessType
     * @covers ::withMaxRetries
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedLivenessCheck::__construct
     */
    public function shouldBuildWithCustomLivenessType()
    {
        $result = (new RequestedLivenessCheckBuilder())
            ->forLivenessType(self::SOME_LIVENESS_TYPE)
            ->withMaxRetries(self::SOME_MAX_RETRIES)
            ->build();

        $expected = [
            'type' => 'LIVENESS',
            'config' => [
                'liveness_type' => self::SOME_LIVENESS_TYPE,
                'max_retries' => self::SOME_MAX_RETRIES,
            ]
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedLivenessCheck::jsonSerialize
     * @covers ::forZoomLiveness
     * @covers ::withMaxRetries
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedLivenessCheck::__construct
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedLivenessCheck::getType
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedLivenessCheck::getConfig
     */
    public function shouldBuildWithZoomLivenessType()
    {
        $result = (new RequestedLivenessCheckBuilder())
            ->forZoomLiveness()
            ->withMaxRetries(self::SOME_MAX_RETRIES)
            ->build();

        $expected = [
            'type' => 'LIVENESS',
            'config' => [
                'liveness_type' => 'ZOOM',
                'max_retries' => self::SOME_MAX_RETRIES,
            ]
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }
}
