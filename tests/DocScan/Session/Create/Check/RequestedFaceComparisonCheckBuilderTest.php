<?php

namespace Yoti\Test\IDV\Session\Create\Check;

use Yoti\IDV\Session\Create\Check\RequestedFaceComparisonCheck;
use Yoti\IDV\Session\Create\Check\RequestedFaceComparisonCheckBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Create\Check\RequestedFaceComparisonCheckBuilder
 */
class RequestedFaceComparisonCheckBuilderTest extends TestCase
{
    /**
     * @test
     * @covers ::withManualCheckAlways
     * @covers ::setManualCheck
     * @covers ::build
     */
    public function shouldCorrectlyBuildRequestedFaceMatchCheck()
    {
        $result = (new RequestedFaceComparisonCheckBuilder())
            ->withManualCheckNever()
            ->build();

        $this->assertInstanceOf(RequestedFaceComparisonCheck::class, $result);
    }

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\Check\RequestedFaceComparisonCheck::jsonSerialize
     * @covers ::withManualCheckNever
     * @covers ::setManualCheck
     * @covers ::build
     * @covers \Yoti\IDV\Session\Create\Check\RequestedFaceComparisonCheck::__construct
     * @covers \Yoti\IDV\Session\Create\Check\RequestedFaceComparisonCheck::getConfig
     * @covers \Yoti\IDV\Session\Create\Check\RequestedFaceComparisonCheck::getType
     */
    public function shouldReturnTheCorrectValuesWhenManualCheckIsNever()
    {
        $result = (new RequestedFaceComparisonCheckBuilder())
            ->withManualCheckNever()
            ->build();

        $expected = [
            'type' => 'FACE_COMPARISON',
            'config' => [
                'manual_check' => 'NEVER',
            ],
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }
}
