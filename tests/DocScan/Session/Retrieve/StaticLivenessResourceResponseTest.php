<?php

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\MediaResponse;
use Yoti\DocScan\Session\Retrieve\StaticLivenessResourceResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\StaticLivenessResourceResponse
 */
class StaticLivenessResourceResponseTest extends TestCase
{
    private const SOME_LIVENESS_TYPE = 'someLivenessType';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getLivenessType
     * @covers ::getImage
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'liveness_type' => self::SOME_LIVENESS_TYPE,
            'image' => [
                'media' => [
                    'id' => '349c30c35dc34c',
                    'type' => 'IMAGE',
                    'created' => '2021-06-11T11:39:24Z',
                    'last_updated' => '2021-06-11T11:39:24Z',
                ]
            ],

        ];

        $result = new StaticLivenessResourceResponse($input);

        $this->assertEquals(self::SOME_LIVENESS_TYPE, $result->getLivenessType());
        $this->assertNotNull($result->getImage());
        $this->assertInstanceOf(MediaResponse::class, $result->getImage());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getLivenessType
     * @covers ::getImage
     */
    public function shouldNotThrowExceptionIfMissingValues()
    {
        $result = new StaticLivenessResourceResponse([]);

        $this->assertNull($result->getLivenessType());
        $this->assertNull($result->getImage());
    }
}
