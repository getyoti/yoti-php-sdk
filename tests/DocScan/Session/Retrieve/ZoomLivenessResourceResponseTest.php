<?php

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\ZoomLivenessResourceResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\ZoomLivenessResourceResponse
 */
class ZoomLivenessResourceResponseTest extends TestCase
{

    /**
     * @test
     * @covers ::__construct
     * @covers ::parseFrames
     * @covers ::getFaceMap
     * @covers ::getFrames
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'facemap' => [],
            'frames' => [
                [ 'someFrameKey' => 'someFrameValue' ],
                [ 'someSecondFrameKey' => 'someSecondFrameValue' ],
            ],
        ];

        $result = new ZoomLivenessResourceResponse($input);

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

        $this->assertNull($result->getFaceMap());
        $this->assertCount(0, $result->getFrames());
    }
}
