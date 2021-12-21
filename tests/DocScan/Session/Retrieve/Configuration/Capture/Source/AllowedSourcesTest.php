<?php

namespace Yoti\Test\DocScan\Session\Retrieve\Configuration\Capture\Source;

use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\EndUserAllowedSourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\IbvAllowedSourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\RelyingBusinessAllowedSourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\UnknownAllowedSourceResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\AllowedSourceResponse
 */
class AllowedSourcesTest extends TestCase
{
    private const END_USER = 'END_USER';
    private const RELYING_BUSINESS = 'RELYING_BUSINESS';
    private const IBV = 'IBV';

    /**
     * @test
     * @covers ::getType
     * @covers \Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\IbvAllowedSourceResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\RelyingBusinessAllowedSourceResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\EndUserAllowedSourceResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\UnknownAllowedSourceResponse::__construct
     */
    public function shouldBuildCorrectly()
    {

        $endUserSource = new EndUserAllowedSourceResponse();
        $relyingBusinessSource = new RelyingBusinessAllowedSourceResponse();
        $ibvSource = new IbvAllowedSourceResponse();
        $unknownSource = new UnknownAllowedSourceResponse();

        $this->assertEquals(self::END_USER, $endUserSource->getType());
        $this->assertEquals(self::RELYING_BUSINESS, $relyingBusinessSource->getType());
        $this->assertEquals(self::IBV, $ibvSource->getType());

        $this->assertInstanceOf(UnknownAllowedSourceResponse::class, $unknownSource);
    }
}
