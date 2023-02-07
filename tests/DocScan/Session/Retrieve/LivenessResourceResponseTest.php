<?php

namespace Yoti\Test\IDV\Session\Retrieve;

use Yoti\IDV\Session\Retrieve\LivenessResourceResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Retrieve\LivenessResourceResponse
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
