<?php

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\LivenessResourceResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\LivenessResourceResponse
 */
class LivenessResourceResponseTest extends TestCase
{
    private const SOME_LIVENESS_TYPE = 'someLivenessType';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getLivenessType
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'liveness_type' => self::SOME_LIVENESS_TYPE,
        ];

        $result = new LivenessResourceResponse($input);

        $this->assertEquals(self::SOME_LIVENESS_TYPE, $result->getLivenessType());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getLivenessType
     */
    public function shouldNotThrowExceptionIfMissingValues()
    {
        $result = new LivenessResourceResponse([]);

        $this->assertNull($result->getLivenessType());
    }
}
