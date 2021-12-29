<?php

namespace Yoti\Test\DocScan\Session\Retrieve\Instructions\Branch;

use Yoti\DocScan\Session\Retrieve\Instructions\Branch\LocationResponse;
use Yoti\DocScan\Session\Retrieve\Instructions\Branch\UkPostOfficeBranchResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\Instructions\Branch\UkPostOfficeBranchResponse
 */
class UkPostOfficeBranchResponseTest extends TestCase
{
    private const SOME_TYPE = 'SOME_TYPE';
    private const SOME_NAME = 'SOME_NAME';
    private const SOME_ADDRESS = 'SOME_ADDRESS';
    private const SOME_POST_CODE = 'SOME_POST_CODE';
    private const SOME_FAD_CODE = 'SOME_FAD_CODE';
    private const SOME_LATITUDE = 0.0873;
    private const SOME_LONGITUDE = 0.836793;

    /**
     * @test
     * @covers ::__construct
     * @covers ::getName
     * @covers ::getFadCode
     * @covers ::getAddress
     * @covers ::getPostCode
     * @covers ::getLocation
     * @covers \Yoti\DocScan\Session\Retrieve\Instructions\Branch\BranchResponse::getType
     */
    public function shouldBuildCorrectly(): void
    {
        $data = [
            'type' => self::SOME_TYPE,
            'fad_code' => self::SOME_FAD_CODE,
            'name' => self::SOME_NAME,
            'address' => self::SOME_ADDRESS,
            'post_code' => self::SOME_POST_CODE,
            'location' => [
                'latitude' => self::SOME_LATITUDE,
                'longitude' => self::SOME_LONGITUDE,
            ]
        ];

        $result = new UkPostOfficeBranchResponse($data);

        $this->assertEquals(self::SOME_TYPE, $result->getType());
        $this->assertEquals(self::SOME_NAME, $result->getName());
        $this->assertEquals(self::SOME_POST_CODE, $result->getPostCode());
        $this->assertEquals(self::SOME_ADDRESS, $result->getAddress());
        $this->assertEquals(self::SOME_FAD_CODE, $result->getFadCode());
        $this->assertInstanceOf(LocationResponse::class, $result->getLocation());
    }
}
