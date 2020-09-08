<?php

namespace Yoti\Test\DocScan\Session\Create\Check;

use Yoti\DocScan\Session\Create\Check\RequestedFaceMatchCheck;
use Yoti\DocScan\Session\Create\Check\RequestedFaceMatchCheckBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Check\RequestedFaceMatchCheckBuilder
 */
class RequestedFaceMatchCheckBuilderTest extends TestCase
{

    /**
     * @test
     * @covers ::withManualCheckAlways
     * @covers ::setManualCheck
     * @covers ::build
     */
    public function shouldCorrectlyBuildRequestedFaceMatchCheck()
    {
        $result = (new RequestedFaceMatchCheckBuilder())
            ->withManualCheckAlways()
            ->build();

        $this->assertInstanceOf(RequestedFaceMatchCheck::class, $result);
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedFaceMatchCheck::jsonSerialize
     * @covers ::withManualCheckAlways
     * @covers ::setManualCheck
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedFaceMatchCheck::__construct
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedFaceMatchCheck::getConfig
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedFaceMatchCheck::getType
     */
    public function shouldReturnTheCorrectValuesWhenManualCheckIsAlways()
    {
        $result = (new RequestedFaceMatchCheckBuilder())
            ->withManualCheckAlways()
            ->build();

        $expected = [
            'type' => 'ID_DOCUMENT_FACE_MATCH',
            'config' => [
                'manual_check' => 'ALWAYS',
            ],
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedFaceMatchCheck::jsonSerialize
     * @covers ::withManualCheckFallback
     * @covers ::setManualCheck
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedFaceMatchCheck::__construct
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedFaceMatchCheck::getConfig
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedFaceMatchCheck::getType
     */
    public function shouldReturnTheCorrectValuesWhenManualCheckIsFallback()
    {
        $result = (new RequestedFaceMatchCheckBuilder())
            ->withManualCheckFallback()
            ->build();

        $expected = [
            'type' => 'ID_DOCUMENT_FACE_MATCH',
            'config' => [
                'manual_check' => 'FALLBACK',
            ],
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedFaceMatchCheck::jsonSerialize
     * @covers ::withManualCheckNever
     * @covers ::setManualCheck
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedFaceMatchCheck::__construct
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedFaceMatchCheck::getConfig
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedFaceMatchCheck::getType
     */
    public function shouldReturnTheCorrectValuesWhenManualCheckIsNever()
    {
        $result = (new RequestedFaceMatchCheckBuilder())
            ->withManualCheckNever()
            ->build();

        $expected = [
            'type' => 'ID_DOCUMENT_FACE_MATCH',
            'config' => [
                'manual_check' => 'NEVER',
            ],
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }
}
