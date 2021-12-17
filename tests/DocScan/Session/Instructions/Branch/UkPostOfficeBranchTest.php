<?php

namespace Yoti\Test\DocScan\Session\Instructions\Branch;

use Yoti\DocScan\Session\Instructions\Branch\Location;
use Yoti\DocScan\Session\Instructions\Branch\UkPostOfficeBranchBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Instructions\Branch\UkPostOfficeBranch
 */
class UkPostOfficeBranchTest extends TestCase
{
    private const SOME_FAD_CODE = "someFadCode";
    private const SOME_NAME = "someName";
    private const SOME_ADDRESS = "someAddress";
    private const SOME_POST_CODE = "somePostCode";

    /**
     * @var Location
     */
    private $locationMock;

    public function setup(): void
    {
        parent::setup();
        $this->locationMock = $this->createMock(Location::class);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getLocation
     * @covers ::getFadCode
     * @covers ::getAddress
     * @covers ::getName
     * @covers ::getPostCode
     * @covers \Yoti\DocScan\Session\Instructions\Branch\Branch::getType
     * @covers \Yoti\DocScan\Session\Instructions\Branch\UkPostOfficeBranchBuilder::build
     * @covers \Yoti\DocScan\Session\Instructions\Branch\UkPostOfficeBranchBuilder::withLocation
     * @covers \Yoti\DocScan\Session\Instructions\Branch\UkPostOfficeBranchBuilder::withPostCode
     * @covers \Yoti\DocScan\Session\Instructions\Branch\UkPostOfficeBranchBuilder::withName
     * @covers \Yoti\DocScan\Session\Instructions\Branch\UkPostOfficeBranchBuilder::withFadCode
     * @covers \Yoti\DocScan\Session\Instructions\Branch\UkPostOfficeBranchBuilder::withAddress
     */
    public function builderShouldBuildWithAllProperties()
    {
        $result = (new UkPostOfficeBranchBuilder())
            ->withAddress(self::SOME_ADDRESS)
            ->withFadCode(self::SOME_FAD_CODE)
            ->withName(self::SOME_NAME)
            ->withPostCode(self::SOME_POST_CODE)
            ->withLocation($this->locationMock)
            ->build();

        $this->assertEquals(self::SOME_POST_CODE, $result->getPostCode());
        $this->assertEquals(self::SOME_ADDRESS, $result->getAddress());
        $this->assertEquals(self::SOME_FAD_CODE, $result->getFadCode());
        $this->assertEquals(self::SOME_NAME, $result->getName());
        $this->assertEquals("UK_POST_OFFICE", $result->getType());
        $this->assertEquals($this->locationMock, $result->getLocation());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getLocation
     * @covers ::getFadCode
     * @covers \Yoti\DocScan\Session\Instructions\Branch\Branch::__construct
     * @covers \Yoti\DocScan\Session\Instructions\Branch\UkPostOfficeBranchBuilder::build
     * @covers \Yoti\DocScan\Session\Instructions\Branch\UkPostOfficeBranchBuilder::withPostCode
     * @covers \Yoti\DocScan\Session\Instructions\Branch\UkPostOfficeBranchBuilder::withName
     * @covers \Yoti\DocScan\Session\Instructions\Branch\UkPostOfficeBranchBuilder::withAddress
     */
    public function shouldBuildWithRequiredProperties()
    {
        $result = (new UkPostOfficeBranchBuilder())
            ->withAddress(self::SOME_ADDRESS)
            ->withName(self::SOME_NAME)
            ->withPostCode(self::SOME_POST_CODE)
            ->build();

        $this->assertNull($result->getLocation());
        $this->assertNull($result->getFadCode());
    }
}
