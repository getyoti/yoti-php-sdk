<?php

namespace Yoti\Test\DocScan\Session\Create;

use Yoti\DocScan\Session\Create\IbvOptionsBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\IbvOptionsBuilder
 */
class IbvOptionsBuilderTest extends TestCase
{
    private const SOME_SUPPORT_VALUE = "someSupportValue";
    private const SOME_GUIDANCE_URL = "someGuidanceUrl";

    /**
     * @test
     * @covers ::build
     * @covers ::withIbvMandatory
     * @covers \Yoti\DocScan\Session\Create\IbvOptions::__construct
     * @covers \Yoti\DocScan\Session\Create\IbvOptions::getSupport
     */
    public function builderWithMandatorySupportShouldSetCorrectSupportValue()
    {
        $result = (new IbvOptionsBuilder())
            ->withIbvMandatory()
            ->build();

        $this->assertEquals('MANDATORY', $result->getSupport());
    }

    /**
     * @test
     * @covers ::build
     * @covers ::withIbvNotAllowed
     * @covers \Yoti\DocScan\Session\Create\IbvOptions::__construct
     * @covers \Yoti\DocScan\Session\Create\IbvOptions::getSupport
     */
    public function builderWithNotAllowedSupportShouldSetCorrectSupportValue()
    {
        $result = (new IbvOptionsBuilder())
            ->withIbvNotAllowed()
            ->build();

        $this->assertEquals('NOT_ALLOWED', $result->getSupport());
    }

    /**
     * @test
     * @covers ::build
     * @covers ::withSupport
     * @covers \Yoti\DocScan\Session\Create\IbvOptions::__construct
     * @covers \Yoti\DocScan\Session\Create\IbvOptions::getSupport
     */
    public function builderShouldAllowUserOverrideOfSupportValue()
    {
        $result = (new IbvOptionsBuilder())
            ->withSupport(self::SOME_SUPPORT_VALUE)
            ->build();

        $this->assertEquals(self::SOME_SUPPORT_VALUE, $result->getSupport());
    }

    /**
     * @test
     * @covers ::build
     * @covers ::withIbvMandatory
     * @covers ::withGuidanceUrl
     * @covers \Yoti\DocScan\Session\Create\IbvOptions::__construct
     * @covers \Yoti\DocScan\Session\Create\IbvOptions::getGuidanceUrl
     */
    public function builderShouldSetCorrectGuidanceUrlValue()
    {
        $result = (new IbvOptionsBuilder())
            ->withIbvMandatory()
            ->withGuidanceUrl(self::SOME_GUIDANCE_URL)
            ->build();

        $this->assertEquals(self::SOME_GUIDANCE_URL, $result->getGuidanceUrl());
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\IbvOptions::jsonSerialize
     */
    public function shouldSerializeWithCorrectProperties(): void
    {
        $result = (new IbvOptionsBuilder())
            ->withIbvNotAllowed()
            ->withGuidanceUrl(self::SOME_GUIDANCE_URL)
            ->build();

        $expected = [
            'support' => 'NOT_ALLOWED',
            'guidance_url' => self::SOME_GUIDANCE_URL,
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }
}
