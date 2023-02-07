<?php

declare(strict_types=1);

namespace Yoti\Test\IDV\Session\Retrieve;

use Yoti\IDV\Session\Retrieve\ResourceContainer;
use Yoti\IDV\Session\Retrieve\StaticLivenessResourceResponse;
use Yoti\IDV\Session\Retrieve\ZoomLivenessResourceResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Retrieve\ResourceContainer
 */
class ResourceContainerTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     * @covers ::parseIdDocuments
     * @covers ::parseLivenessCapture
     * @covers ::getIdDocuments
     * @covers ::getLivenessCapture
     * @covers ::getStaticLivenessResources
     * @covers ::getZoomLivenessResources
     * @covers ::getFaceCapture
     * @covers ::parseFaceCapture
     * @covers ::parseSupplementaryDocuments
     * @covers ::getSupplementaryDocuments
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'id_documents' => [
                [],
                []
            ],
            'supplementary_documents' => [
                [],
                []
            ],
            'liveness_capture' => [
                [ 'liveness_type' => 'ZOOM' ],
                [ 'liveness_type' => 'STATIC' ],
                [ 'liveness_type' => 'someUnknownType' ],
            ],
            'face_capture' => [
                ['id' => 'SOME_ID']
            ]
        ];

        $result = new ResourceContainer($input);

        $this->assertCount(2, $result->getIdDocuments());
        $this->assertCount(3, $result->getLivenessCapture());
        $this->assertCount(1, $result->getZoomLivenessResources());
        $this->assertCount(1, $result->getStaticLivenessResources());
        $this->assertCount(2, $result->getSupplementaryDocuments());
        $this->assertCount(1, $result->getFaceCapture());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenMissingValues()
    {
        $result = new ResourceContainer([]);

        $this->assertCount(0, $result->getIdDocuments());
        $this->assertCount(0, $result->getLivenessCapture());
    }

    /**
     * @test
     * @covers ::parseLivenessCapture
     */
    public function shouldHandleZoomLivenessCapture()
    {
        $input = [
            'liveness_capture' => [
                [ 'liveness_type' => 'ZOOM' ]
            ],
        ];

        $result = new ResourceContainer($input);

        $this->assertCount(1, $result->getLivenessCapture());
        $this->assertInstanceOf(ZoomLivenessResourceResponse::class, $result->getLivenessCapture()[0]);
    }

    /**
     * @test
     * @covers ::parseLivenessCapture
     * @covers ::getStaticLivenessResources
     * @covers ::getLivenessCapture
     */
    public function shouldHandleStaticLivenessCapture()
    {
        $input = [
            'liveness_capture' => [
                [ 'liveness_type' => 'STATIC' ]
            ],
        ];

        $result = new ResourceContainer($input);

        $this->assertCount(1, $result->getLivenessCapture());
        $this->assertInstanceOf(StaticLivenessResourceResponse::class, $result->getLivenessCapture()[0]);
    }

    /**
     * @test
     * @covers ::getZoomLivenessResources
     * @covers ::filterLivenessByType
     */
    public function shouldFilterZoomLivenessResources(): void
    {
        $input = [
            'liveness_capture' => [
                [ 'liveness_type' => 'ZOOM' ],
                [ 'liveness_type' => 'ZOOM' ],
                [ 'liveness_type' => 'someUnknownType' ]
            ],
        ];

        $result = new ResourceContainer($input);

        $this->assertCount(3, $result->getLivenessCapture());
        $this->assertCount(2, $result->getZoomLivenessResources());
    }
}
