<?php

namespace Yoti\Test\IDV\Session\Create\Check;

use Yoti\IDV\Session\Create\Check\RequestedLivenessCheck;
use Yoti\IDV\Session\Create\Check\RequestedLivenessCheckBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Create\Check\RequestedLivenessCheckBuilder
 */
class RequstedLivenessCheckBuilderTest extends TestCase
{
    private const SOME_LIVENESS_TYPE = 'someLivenessType';
    private const SOME_MAX_RETRIES = 3;
    private const NEVER = 'NEVER';

    /**
     * @test
     * @covers ::build
     * @covers ::forLivenessType
     * @covers ::withMaxRetries
     * @covers \Yoti\IDV\Session\Create\Check\RequestedLivenessCheck::__construct
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
     * @covers \Yoti\IDV\Session\Create\Check\RequestedLivenessCheck::jsonSerialize
     * @covers ::forLivenessType
     * @covers ::withMaxRetries
     * @covers \Yoti\IDV\Session\Create\Check\RequestedLivenessCheck::__construct
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
     * @covers \Yoti\IDV\Session\Create\Check\RequestedLivenessCheck::jsonSerialize
     * @covers ::forZoomLiveness
     * @covers ::withMaxRetries
     * @covers \Yoti\IDV\Session\Create\Check\RequestedLivenessCheck::__construct
     * @covers \Yoti\IDV\Session\Create\Check\RequestedLivenessCheck::getType
     * @covers \Yoti\IDV\Session\Create\Check\RequestedLivenessCheck::getConfig
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

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\Check\RequestedLivenessCheck::jsonSerialize
     * @covers ::forStaticLiveness
     * @covers ::withManualCheck
     * @covers ::withoutManualCheck
     * @covers ::withMaxRetries
     * @covers \Yoti\IDV\Session\Create\Check\RequestedLivenessCheck::__construct
     * @covers \Yoti\IDV\Session\Create\Check\RequestedLivenessCheck::getType
     * @covers \Yoti\IDV\Session\Create\Check\RequestedLivenessCheck::getConfig
     */
    public function shouldBuildWithStaticLivenessType()
    {
        $result = (new RequestedLivenessCheckBuilder())
            ->forStaticLiveness()
            ->withMaxRetries(self::SOME_MAX_RETRIES)
            ->withoutManualCheck()
            ->build();

        $expected = [
            'type' => 'LIVENESS',
            'config' => [
                'liveness_type' => 'STATIC',
                'max_retries' => self::SOME_MAX_RETRIES,
                'manual_check' => self::NEVER,
            ]
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }
}
