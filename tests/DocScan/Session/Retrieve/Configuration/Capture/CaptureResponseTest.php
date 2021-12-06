<?php

namespace Yoti\Test\DocScan\Session\Retrieve\Configuration\Capture;

use Yoti\DocScan\Session\Retrieve\Configuration\Capture\CaptureResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\UnknownRequiredResourceResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\Configuration\Capture\CaptureResponse
 */
class CaptureResponseTest extends TestCase
{
    private const SOME_BIOMETRIC_CONSENT = 'SOME_BIOMETRIC_CONSENT';
    private const SOME_REQUIRED_RESOURCES = [
        [
            'type' => 'SOME_TYPE',
            'id' => 'SOME_ID',
            'state' => 'SOME_STATE',
            'allowed_sources' => [
                [
                    'type' => 'SOME_TYPE',
                ],
                [
                    'type' => 'SOME_ANOTHER_TYPE',
                ]
            ]
        ]
    ];
    private const SOME_UNKNOWN_TYPE = 'someUnknownType';
    private const ID_DOCUMENT = 'ID_DOCUMENT';
    private const SUPPLEMENTARY_DOCUMENT = 'SUPPLEMENTARY_DOCUMENT';
    private const LIVENESS = 'LIVENESS';
    private const FACE_CAPTURE = 'FACE_CAPTURE';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getBiometricConsent
     * @covers ::getRequiredResources
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'biometric_consent' => self::SOME_BIOMETRIC_CONSENT,
            'required_resources' => self::SOME_REQUIRED_RESOURCES,
        ];

        $result = new CaptureResponse($input);

        $this->assertEquals(self::SOME_BIOMETRIC_CONSENT, $result->getBiometricConsent());
        $this->assertInstanceOf(UnknownRequiredResourceResponse::class, $result->getRequiredResources()[0]);

        $this->assertNotNull($result->getRequiredResources());
        $this->assertCount(1, $result->getRequiredResources());
    }

    /**
     * @test
     */
    public function shouldParseUnknownResource()
    {
        $input = [
            'required_resources' => [
                ['type' => self::SOME_UNKNOWN_TYPE],
            ],
        ];

        $result = new CaptureResponse($input);

        $this->assertCount(1, $result->getRequiredResources());
        $this->assertEquals(self::SOME_UNKNOWN_TYPE, $result->getRequiredResources()[0]->getType());
    }

    /**
     * @test
     * @covers ::getDocumentResourceRequirements
     * @covers ::getIdDocumentResourceRequirements
     * @covers ::getSupplementaryResourceRequirements
     * @covers ::getLivenessResourceRequirements
     * @covers ::getZoomLivenessResourceRequirements
     * @covers ::getFaceCaptureResourceRequirements
     * @covers ::filter
     * @covers ::createRequiredResourcesArray
     */
    public function shouldFilterRequiredSources(): void
    {
        $input = [
            'required_resources' => [
                ['type' => self::ID_DOCUMENT],
                ['type' => self::SUPPLEMENTARY_DOCUMENT],
                ['type' => self::LIVENESS],
                ['type' => self::FACE_CAPTURE],
            ],
        ];

        $result = new CaptureResponse($input);

        $this->assertCount(4, $result->getRequiredResources());
        $this->assertCount(2, $result->getDocumentResourceRequirements());
        $this->assertCount(1, $result->getIdDocumentResourceRequirements());
        $this->assertCount(1, $result->getLivenessResourceRequirements());
        $this->assertCount(1, $result->getSupplementaryResourceRequirements());
        $this->assertCount(1, $result->getZoomLivenessResourceRequirements());
        $this->assertCount(1, $result->getFaceCaptureResourceRequirements());

        $this->assertEquals(
            self::ID_DOCUMENT,
            $result->getIdDocumentResourceRequirements()[0]->getType()
        );
        $this->assertEquals(
            self::SUPPLEMENTARY_DOCUMENT,
            $result->getSupplementaryResourceRequirements()[0]->getType()
        );
        $this->assertEquals(
            self::LIVENESS,
            $result->getZoomLivenessResourceRequirements()[0]->getType()
        );
        $this->assertEquals(
            self::FACE_CAPTURE,
            $result->getFaceCaptureResourceRequirements()[0]->getType()
        );
    }
}
