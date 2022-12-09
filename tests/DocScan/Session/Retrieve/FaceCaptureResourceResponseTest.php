<?php

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\AllowedSourceResponse;
use Yoti\DocScan\Session\Retrieve\FaceCaptureImageResponse;
use Yoti\DocScan\Session\Retrieve\FaceCaptureResourceResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\FaceCaptureResourceResponse
 */
class FaceCaptureResourceResponseTest extends TestCase
{
    private const RELYING_BUSINESS = 'RELYING_BUSINESS';
    private const SOME_ID = '493ru49358gh945fh305';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getImage
     * @covers \Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\AllowedSourceResponse::getType
     * @covers \Yoti\DocScan\Session\Retrieve\ResourceResponse::getSource
     * @covers \Yoti\DocScan\Session\Retrieve\MediaResponse::getId
     * @covers \Yoti\DocScan\Session\Retrieve\FaceCaptureImageResponse::getMedia
     * @covers \Yoti\DocScan\Session\Retrieve\FaceCaptureImageResponse::__construct
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'tasks' => [],
            'source' => [
                'type' => self::RELYING_BUSINESS
            ],
            'image' => [
                'media' => [
                    'id' => self::SOME_ID,
                    'type' => 'IMAGE',
                    'created' => '2021-06-11T11:39:24Z',
                    'last_updated' => '2021-06-11T11:39:24Z',
                ]
            ],

        ];

        $result = new FaceCaptureResourceResponse($input);

        $this->assertEquals(self::RELYING_BUSINESS, $result->getSource()->getType());
        $this->assertInstanceOf(AllowedSourceResponse::class, $result->getSource());

        $this->assertEquals(self::SOME_ID, $result->getImage()->getMedia()->getId());
        $this->assertInstanceOf(FaceCaptureImageResponse::class, $result->getImage());
    }
}
