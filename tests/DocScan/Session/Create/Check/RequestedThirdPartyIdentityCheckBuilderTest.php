<?php

namespace Yoti\Test\IDV\Session\Create\Check;

use Yoti\IDV\Session\Create\Check\RequestedThirdPartyIdentityCheck;
use Yoti\IDV\Session\Create\Check\RequestedThirdPartyIdentityCheckBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Create\Check\RequestedThirdPartyIdentityCheckBuilder
 */
class RequestedThirdPartyIdentityCheckBuilderTest extends TestCase
{
    private const THIRD_PARTY_IDENTITY = 'THIRD_PARTY_IDENTITY';

    /**
     * @test
     * @covers ::build
     * @covers \Yoti\IDV\Session\Create\Check\RequestedThirdPartyIdentityCheck::getConfig
     * @covers \Yoti\IDV\Session\Create\Check\RequestedThirdPartyIdentityCheck::getType
     */
    public function shouldCreateRequestedThirdPartyIdentityCheckCorrectly()
    {
        $result = (new RequestedThirdPartyIdentityCheckBuilder())
            ->build();

        $this->assertInstanceOf(RequestedThirdPartyIdentityCheck::class, $result);
    }

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\Check\RequestedThirdPartyIdentityCheck::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\Check\RequestedThirdPartyIdentityCheck::getType
     * @covers \Yoti\IDV\Session\Create\Check\RequestedThirdPartyIdentityCheck::getConfig
     */
    public function shouldJsonEncodeCorrectly()
    {
        $result = (new RequestedThirdPartyIdentityCheckBuilder())
            ->build();

        $expected = [
            'type' => self::THIRD_PARTY_IDENTITY,
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\Check\RequestedThirdPartyIdentityCheck::__toString
     */
    public function shouldCreateCorrectString()
    {
        $result = (new RequestedThirdPartyIdentityCheckBuilder())
            ->build();

        $expected = [
            'type' => self::THIRD_PARTY_IDENTITY,
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), $result->__toString());
    }
}
