<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\ResourceContainer;
use Yoti\DocScan\Session\Retrieve\ZoomLivenessResourceResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\ResourceContainer
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
                [ 'liveness_type' => 'someUnknownType' ],
            ],
        ];

        $result = new ResourceContainer($input);

        $this->assertCount(2, $result->getIdDocuments());
        $this->assertCount(2, $result->getLivenessCapture());
        $this->assertCount(1, $result->getZoomLivenessResources());
        $this->assertCount(2, $result->getSupplementaryDocuments());
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
