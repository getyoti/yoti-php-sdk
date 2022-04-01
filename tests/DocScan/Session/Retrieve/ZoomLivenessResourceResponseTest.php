<?php

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\ZoomLivenessResourceResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\ZoomLivenessResourceResponse
 */
class ZoomLivenessResourceResponseTest extends TestCase
{
    private const SOME_LIVENESS_TYPE = 'someLivenessType';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getLivenessType
     * @covers ::getFaceMap
     * @covers ::getFrames
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'liveness_type' => self::SOME_LIVENESS_TYPE,
            'facemap' => [],
            'frames' => [
                [ 'someFrameKey' => 'someFrameValue' ],
                [ 'someSecondFrameKey' => 'someSecondFrameValue' ],
            ],
        ];

        $result = new ZoomLivenessResourceResponse($input);

        $this->assertEquals(self::SOME_LIVENESS_TYPE, $result->getLivenessType());
        $this->assertNotNull($result->getFaceMap());
        $this->assertCount(2, $result->getFrames());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionIfMissingValues()
    {
        $result = new ZoomLivenessResourceResponse([]);

        $this->assertNull($result->getLivenessType());
        $this->assertNull($result->getFaceMap());
        $this->assertCount(0, $result->getFrames());
    }
}
