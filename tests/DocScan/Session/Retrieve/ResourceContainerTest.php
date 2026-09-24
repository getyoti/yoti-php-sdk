<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\ApplicantProfileResourceResponse;
use Yoti\DocScan\Session\Retrieve\CheckResponse;
use Yoti\DocScan\Session\Retrieve\ResourceContainer;
use Yoti\DocScan\Session\Retrieve\ShareCodeResourceResponse;
use Yoti\DocScan\Session\Retrieve\StaticLivenessResourceResponse;
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
     * @covers ::getStaticLivenessResources
     * @covers ::getZoomLivenessResources
     * @covers ::getFaceCapture
     * @covers ::parseFaceCapture
     * @covers ::parseSupplementaryDocuments
     * @covers ::getSupplementaryDocuments
     * @covers ::parseShareCodes
     * @covers ::getShareCodes
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
            ],
            'share_codes' => [
                ['id' => 'share-code-1'],
                ['id' => 'share-code-2'],
            ],
            'applicant_profiles' => [
                ['id' => 'applicant-profile-1'],
            ],
        ];

        $result = new ResourceContainer($input);

        $this->assertCount(2, $result->getIdDocuments());
        $this->assertCount(3, $result->getLivenessCapture());
        $this->assertCount(1, $result->getZoomLivenessResources());
        $this->assertCount(1, $result->getStaticLivenessResources());
        $this->assertCount(2, $result->getSupplementaryDocuments());
        $this->assertCount(1, $result->getFaceCapture());
        $this->assertCount(2, $result->getShareCodes());
        $this->assertCount(1, $result->getApplicantProfiles());
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
        $this->assertCount(0, $result->getShareCodes());
        $this->assertCount(0, $result->getApplicantProfiles());
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

    /**
     * @test
     * @covers ::parseShareCodes
     * @covers ::getShareCodes
     */
    public function shouldParseShareCodes(): void
    {
        $input = [
            'share_codes' => [
                [
                    'id' => 'share-code-1',
                    'source' => ['type' => 'END_USER'],
                    'created_at' => '2026-01-14T10:00:00Z',
                    'last_updated' => '2026-01-14T11:00:00Z',
                    'tasks' => [],
                ],
                [
                    'id' => 'share-code-2',
                    'source' => ['type' => 'END_USER'],
                    'created_at' => '2026-01-14T12:00:00Z',
                    'last_updated' => '2026-01-14T13:00:00Z',
                    'tasks' => [],
                ],
            ],
        ];

        $result = new ResourceContainer($input);

        $this->assertCount(2, $result->getShareCodes());
        $this->assertContainsOnlyInstancesOf(
            ShareCodeResourceResponse::class,
            $result->getShareCodes()
        );
        $this->assertEquals('share-code-1', $result->getShareCodes()[0]->getId());
        $this->assertEquals('share-code-2', $result->getShareCodes()[1]->getId());
    }

    /**
     * @test
     * @covers ::parseApplicantProfiles
     * @covers ::getApplicantProfiles
     */
    public function shouldParseApplicantProfiles(): void
    {
        $input = [
            'applicant_profiles' => [
                [
                    'id' => '3fa85f64-5717-4562-b3fc-2c963f66afa6',
                    'source' => ['type' => 'END_USER'],
                    'media' => [
                        'id' => 'media-id-123',
                        'type' => 'IMAGE',
                        'created' => '2021-06-11T11:39:24Z',
                        'last_updated' => '2021-06-11T11:39:24Z',
                    ],
                    'created_at' => '2021-06-11T11:39:24Z',
                    'last_updated' => '2021-06-11T11:39:24Z',
                    'tasks' => [],
                ],
            ],
        ];

        $result = new ResourceContainer($input);

        $this->assertCount(1, $result->getApplicantProfiles());
        $this->assertContainsOnlyInstancesOf(
            ApplicantProfileResourceResponse::class,
            $result->getApplicantProfiles()
        );
        $this->assertEquals(
            '3fa85f64-5717-4562-b3fc-2c963f66afa6',
            $result->getApplicantProfiles()[0]->getId()
        );
    }

    /**
     * @test
     * @covers ::filterForCheck
     * @covers ::filterResourcesByIds
     */
    public function shouldFilterForCheckAndReturnFilteredCollections(): void
    {
        $input = [
            'id_documents' => [
                ['id' => 'id-document-1'],
                ['id' => 'id-document-2'],
            ],
            'supplementary_documents' => [
                ['id' => 'supplementary-document-1'],
                ['id' => 'supplementary-document-2'],
            ],
            'liveness_capture' => [
                ['id' => 'liveness-1', 'liveness_type' => 'ZOOM'],
                ['id' => 'liveness-2', 'liveness_type' => 'ZOOM'],
            ],
            'face_capture' => [
                ['id' => 'face-capture-1'],
                ['id' => 'face-capture-2'],
            ],
            'share_codes' => [
                ['id' => 'share-code-1'],
                ['id' => 'share-code-2'],
            ],
            'applicant_profiles' => [
                ['id' => 'applicant-profile-1'],
                ['id' => 'applicant-profile-2'],
            ],
        ];

        $checkResponse = new CheckResponse([
            'resources_used' => [
                'id-document-1',
                'supplementary-document-1',
                'liveness-1',
                'face-capture-1',
                'share-code-1',
                'applicant-profile-1',
            ],
        ]);

        $result = (new ResourceContainer($input))->filterForCheck($checkResponse);

        $this->assertCount(1, $result->getIdDocuments());
        $this->assertEquals('id-document-1', $result->getIdDocuments()[0]->getId());

        $this->assertCount(1, $result->getSupplementaryDocuments());
        $this->assertEquals('supplementary-document-1', $result->getSupplementaryDocuments()[0]->getId());

        $this->assertCount(1, $result->getLivenessCapture());
        $this->assertEquals('liveness-1', $result->getLivenessCapture()[0]->getId());

        $this->assertCount(1, $result->getFaceCapture());
        $this->assertEquals('face-capture-1', $result->getFaceCapture()[0]->getId());

        $this->assertCount(1, $result->getShareCodes());
        $this->assertEquals('share-code-1', $result->getShareCodes()[0]->getId());

        $this->assertCount(1, $result->getApplicantProfiles());
        $this->assertEquals('applicant-profile-1', $result->getApplicantProfiles()[0]->getId());
    }

    /**
     * @test
     * @covers ::filterForCheck
     * @covers ::filterResourcesByIds
     */
    public function shouldFilterForCheckAndReturnEmptyCollectionsWhenNoMatches(): void
    {
        $input = [
            'id_documents' => [
                ['id' => 'id-document-1'],
            ],
        ];

        $checkResponse = new CheckResponse(['resources_used' => ['someUnrelatedResourceId']]);

        $result = (new ResourceContainer($input))->filterForCheck($checkResponse);

        $this->assertCount(0, $result->getIdDocuments());
        $this->assertCount(0, $result->getSupplementaryDocuments());
        $this->assertCount(0, $result->getLivenessCapture());
        $this->assertCount(0, $result->getFaceCapture());
        $this->assertCount(0, $result->getShareCodes());
        $this->assertCount(0, $result->getApplicantProfiles());
    }

    /**
     * @test
     * @covers ::filterForCheck
     * @covers ::filterResourcesByIds
     */
    public function shouldFilterForCheckAndReturnEmptyCollectionsWhenContainerHasNoResources(): void
    {
        $checkResponse = new CheckResponse(['resources_used' => ['someResourceId']]);

        $result = (new ResourceContainer([]))->filterForCheck($checkResponse);

        $this->assertCount(0, $result->getIdDocuments());
        $this->assertCount(0, $result->getSupplementaryDocuments());
        $this->assertCount(0, $result->getLivenessCapture());
        $this->assertCount(0, $result->getFaceCapture());
        $this->assertCount(0, $result->getShareCodes());
        $this->assertCount(0, $result->getApplicantProfiles());
    }
}
