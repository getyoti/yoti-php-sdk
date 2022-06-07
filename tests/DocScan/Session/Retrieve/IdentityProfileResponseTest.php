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
     * @covers \Yoti\DocScan\Session\Retrieve\IdentityProfile\FailureReasonResponse::getStringCode
     * @covers \Yoti\DocScan\Session\Retrieve\IdentityProfile\FailureReasonResponse::__construct
     */
    public function shouldCreatedCorrectly(): void
    {
        $testData = [
            'subject_id' => self::SUBJECT_ID,
            'result' => self::RESULT,
            'failure_reason' => [
                'reason_code' => self::REASON_CODE,
            ],
            'identity_profile_report' => self::IDENTITY_PROFILE_REPORT,
        ];

        $result = new IdentityProfileResponse($testData);

        $this->assertEquals(self::RESULT, $result->getResult());
        $this->assertEquals(self::SUBJECT_ID, $result->getSubjectId());
        $this->assertEquals((object)self::IDENTITY_PROFILE_REPORT, $result->getIdentityProfileReport());

        $this->assertInstanceOf(FailureReasonResponse::class, $result->getFailureReason());
        $this->assertEquals(self::REASON_CODE, $result->getFailureReason()->getStringCode());
    }
}
