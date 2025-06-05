<?php

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\IdentityProfile\FailureReasonResponse;
use Yoti\DocScan\Session\Retrieve\IdentityProfileResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\IdentityProfileResponse
 */
class IdentityProfileResponseTest extends TestCase
{
    private const RESULT = 'DONE';
    private const SUBJECT_ID = 'someStringHere';
    private const FAILURE_TYPE = 'someStringHere';
    private const DOCUMENT_TYPE = 'someStringHere';
    private const DOCUMENT_COUNTRY_ISO_CODE = 'someStringHere';
    private const AUDIT_ID = 'someStringHere';
    private const DETAILS = 'someStringHere';
    private const REASON_CODE = 'MANDATORY_DOCUMENT_COULD_NOT_BE_PROVIDED';
    private const IDENTITY_PROFILE_REPORT = [
        'trust_framework' => 'UK_TFIDA',
        'schemes_compliance' => [
            0 => [
                'scheme' => [
                    'type' => 'DBS',
                    'objective' => 'STANDARD',
                ],
                'requirements_met' => true,
                'requirements_not_met_info' => 'some string here',
            ],
        ],
        'media' => [
        ],
    ];

    /**
     * @test
     * @covers ::__construct
     * @covers ::getIdentityProfileReport
     * @covers ::getFailureReason
     * @covers ::getResult
     * @covers ::getSubjectId
     * @covers \Yoti\DocScan\Session\Retrieve\IdentityProfile\FailureReasonResponse::__construct
     */
    public function shouldCreatedCorrectly(): void
    {
        $testData = [
            'subject_id' => self::SUBJECT_ID,
            'result' => self::RESULT,
            'failure_reason' => [
                'reason_code' => self::REASON_CODE,
                'requirements_not_met_details' => [
                    0 => [
                        'failure_type' => self::FAILURE_TYPE,
                        'document_type' => self::DOCUMENT_TYPE,
                        'document_country_iso_code' => self::DOCUMENT_COUNTRY_ISO_CODE,
                        'audit_id' => self::AUDIT_ID,
                        'details' => self::DETAILS
                        ]
                ]
            ],
            'identity_profile_report' => self::IDENTITY_PROFILE_REPORT,
        ];

        $result = new IdentityProfileResponse($testData);
        $this->assertEquals(self::RESULT, $result->getResult());
        $this->assertEquals(self::SUBJECT_ID, $result->getSubjectId());
        $this->assertEquals((object)self::IDENTITY_PROFILE_REPORT, $result->getIdentityProfileReport());
        $this->assertInstanceOf(FailureReasonResponse::class, $result->getFailureReason());
        $this->assertEquals(self::REASON_CODE, $result->getFailureReason()->getReasonCode());
        $requriementNotMetDetailsResponse = $result->getFailureReason()->getRequirementNotMetDetails();
        $this->assertEquals(self::FAILURE_TYPE, $requriementNotMetDetailsResponse->getFailureType());
        $this->assertEquals(self::DOCUMENT_TYPE, $requriementNotMetDetailsResponse->getDocumentType());
        $this->assertEquals(self::AUDIT_ID, $requriementNotMetDetailsResponse->getAuditId());
        $this->assertEquals(self::DETAILS, $requriementNotMetDetailsResponse->getDetails());
    }
}
