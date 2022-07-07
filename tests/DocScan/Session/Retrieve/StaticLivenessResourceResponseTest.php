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
    private const SOME_ID = '493ru49358gh945fh305';

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
                    'id' => self::SOME_ID,
                    'type' => 'IMAGE',
                    'created' => '2021-06-11T11:39:24Z',
                    'last_updated' => '2021-06-11T11:39:24Z',
                ]
            ],

        ];

        $result = new StaticLivenessResourceResponse($input);

        $this->assertEquals(self::SOME_LIVENESS_TYPE, $result->getLivenessType());
        $this->assertEquals(self::SOME_ID, $result->getImage()->getId());
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
