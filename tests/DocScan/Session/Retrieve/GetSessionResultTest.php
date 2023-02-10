<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\AuthenticityCheckResponse;
use Yoti\DocScan\Session\Retrieve\CheckResponse;
use Yoti\DocScan\Session\Retrieve\GetSessionResult;
use Yoti\DocScan\Session\Retrieve\IdentityProfilePreviewResponse;
use Yoti\DocScan\Session\Retrieve\IdentityProfileResponse;
use Yoti\DocScan\Session\Retrieve\ImportTokenResponse;
use Yoti\DocScan\Session\Retrieve\MediaResponse;
use Yoti\DocScan\Session\Retrieve\ThirdPartyIdentityFraudOneCheckResponse;
use Yoti\Test\TestCase;
use Yoti\Util\DateTime;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\GetSessionResult
 */
class GetSessionResultTest extends TestCase
{
    private const ID_DOCUMENT_AUTHENTICITY = 'ID_DOCUMENT_AUTHENTICITY';
    private const ID_DOCUMENT_FACE_MATCH = 'ID_DOCUMENT_FACE_MATCH';
    private const ID_DOCUMENT_TEXT_DATA_CHECK = 'ID_DOCUMENT_TEXT_DATA_CHECK';
    private const ID_DOCUMENT_COMPARISON = 'ID_DOCUMENT_COMPARISON';
    private const THIRD_PARTY_IDENTITY = 'THIRD_PARTY_IDENTITY';
    private const THIRD_PARTY_IDENTITY_FRAUD_1 = "THIRD_PARTY_IDENTITY_FRAUD_1";
    private const WATCHLIST_SCREENING = 'WATCHLIST_SCREENING';
    private const WATCHLIST_ADVANCED_CA = 'WATCHLIST_ADVANCED_CA';
    private const SUPPLEMENTARY_DOCUMENT_TEXT_DATA_CHECK = 'SUPPLEMENTARY_DOCUMENT_TEXT_DATA_CHECK';
    private const LIVENESS = 'LIVENESS';
    private const FACE_COMPARISON = 'FACE_COMPARISON';
    private const SOME_UNKNOWN_TYPE = 'someUnknownType';
    private const SOME_STATE = 'someState';
    private const SOME_SESSION_ID = 'someSessionId';
    private const SOME_USER_TRACKING_ID = 'someUserTrackingId';
    private const SOME_CLIENT_SESSION_TOKEN = 'someClientSessionToken';
    private const SOME_CLIENT_SESSION_TOKEN_TTL = 300;
    private const SOME_BIOMETRIC_CONSENT_DATE_STRING = '2019-12-02T12:00:00.123Z';
    private const IDENTITY_PROFILE = [
        'subject_id' => 'SOME_STRING',
        'result' => 'SOME_ANOTHER_STRING',
        'failure_reason' => [
            'reason_code' => 'ANOTHER_STRING',
        ],
        'identity_profile_report' => [],
    ];

    /**
     * @test
     * @covers ::__construct
     * @covers ::getState
     * @covers ::getSessionId
     * @covers ::getUserTrackingId
     * @covers ::getClientSessionToken
     * @covers ::getClientSessionTokenTtl
     * @covers ::getResources
     * @covers ::getChecks
     * @covers ::getBiometricConsentTimestamp
     * @covers ::getIdentityProfile
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'state' => self::SOME_STATE,
            'session_id' => self::SOME_SESSION_ID,
            'user_tracking_id' => self::SOME_USER_TRACKING_ID,
            'client_session_token' => self::SOME_CLIENT_SESSION_TOKEN,
            'client_session_token_ttl' => self::SOME_CLIENT_SESSION_TOKEN_TTL,
            'biometric_consent' => self::SOME_BIOMETRIC_CONSENT_DATE_STRING,
            'checks' => [
                ['type' => self::ID_DOCUMENT_AUTHENTICITY]
            ],
            'resources' => [],
            'identity_profile' => self::IDENTITY_PROFILE
        ];

        $result = new GetSessionResult($input);

        $this->assertEquals(self::SOME_STATE, $result->getState());
        $this->assertEquals(self::SOME_SESSION_ID, $result->getSessionId());
        $this->assertEquals(self::SOME_USER_TRACKING_ID, $result->getUserTrackingId());
        $this->assertEquals(self::SOME_CLIENT_SESSION_TOKEN, $result->getClientSessionToken());
        $this->assertEquals(self::SOME_CLIENT_SESSION_TOKEN_TTL, $result->getClientSessionTokenTtl());

        $this->assertNotNull($result->getChecks());
        $this->assertCount(1, $result->getChecks());
        $this->assertInstanceOf(AuthenticityCheckResponse::class, $result->getChecks()[0]);
        $this->assertInstanceOf(IdentityProfileResponse::class, $result->getIdentityProfile());

        $this->assertNotNull($result->getResources());

        $this->assertEquals(
            DateTime::stringToDateTime(self::SOME_BIOMETRIC_CONSENT_DATE_STRING),
            $result->getBiometricConsentTimestamp()
        );
    }

    /**
     * @test
     */
    public function shouldParseUnknownCheck()
    {
        $input = [
            'checks' => [
                ['type' => self::SOME_UNKNOWN_TYPE],
            ],
        ];

        $result = new GetSessionResult($input);

        $this->assertCount(1, $result->getChecks());

        $this->assertInstanceOf(CheckResponse::class, $result->getChecks()[0]);
        $this->assertEquals(self::SOME_UNKNOWN_TYPE, $result->getChecks()[0]->getType());
    }

    /**
     * @test
     * @covers ::getAuthenticityChecks
     * @covers ::getFaceMatchChecks
     * @covers ::getTextDataChecks
     * @covers ::getIdDocumentTextDataChecks
     * @covers ::getIdDocumentComparisonChecks
     * @covers ::getThirdPartyIdentityChecks
     * @covers ::getWatchlistScreeningChecks
     * @covers ::getSupplementaryDocumentTextDataChecks
     * @covers ::getLivenessChecks
     * @covers ::getWatchlistAdvancedCaChecks
     * @covers ::getThirdPartyIdentityFraudOneChecks
     * @covers ::getFaceComparisonChecks
     * @covers ::createCheckFromArray
     * @covers ::filterCheckByType
     */
    public function shouldFilterChecks(): void
    {
        $input = [
            'checks' => [
                ['type' => self::ID_DOCUMENT_AUTHENTICITY],
                ['type' => self::ID_DOCUMENT_FACE_MATCH],
                ['type' => self::ID_DOCUMENT_TEXT_DATA_CHECK],
                ['type' => self::ID_DOCUMENT_COMPARISON],
                ['type' => self::THIRD_PARTY_IDENTITY],
                ['type' => self::WATCHLIST_SCREENING],
                ['type' => self::SUPPLEMENTARY_DOCUMENT_TEXT_DATA_CHECK],
                ['type' => self::LIVENESS],
                ['type' => self::WATCHLIST_ADVANCED_CA],
                ['type' => self::THIRD_PARTY_IDENTITY_FRAUD_1],
                ['type' => self::FACE_COMPARISON],
            ],
        ];

        $result = new GetSessionResult($input);

        $this->assertCount(11, $result->getChecks());
        $this->assertCount(1, $result->getAuthenticityChecks());
        $this->assertCount(1, $result->getFaceMatchChecks());
        $this->assertCount(1, $result->getTextDataChecks());
        $this->assertCount(1, $result->getIdDocumentTextDataChecks());
        $this->assertCount(1, $result->getIdDocumentComparisonChecks());
        $this->assertCount(1, $result->getThirdPartyIdentityChecks());
        $this->assertCount(1, $result->getWatchlistScreeningChecks());
        $this->assertCount(1, $result->getSupplementaryDocumentTextDataChecks());
        $this->assertCount(1, $result->getLivenessChecks());
        $this->assertCount(1, $result->getWatchlistAdvancedCaChecks());
        $this->assertCount(1, $result->getThirdPartyIdentityFraudOneChecks());
        $this->assertCount(1, $result->getFaceComparisonChecks());

        $this->assertEquals(
            self::ID_DOCUMENT_AUTHENTICITY,
            $result->getAuthenticityChecks()[0]->getType()
        );
        $this->assertEquals(
            self::ID_DOCUMENT_FACE_MATCH,
            $result->getFaceMatchChecks()[0]->getType()
        );
        $this->assertEquals(
            self::ID_DOCUMENT_TEXT_DATA_CHECK,
            $result->getTextDataChecks()[0]->getType()
        );
        $this->assertEquals(
            self::ID_DOCUMENT_TEXT_DATA_CHECK,
            $result->getIdDocumentTextDataChecks()[0]->getType()
        );
        $this->assertEquals(
            self::ID_DOCUMENT_COMPARISON,
            $result->getIdDocumentComparisonChecks()[0]->getType()
        );
        $this->assertEquals(
            self::THIRD_PARTY_IDENTITY,
            $result->getThirdPartyIdentityChecks()[0]->getType()
        );
        $this->assertEquals(
            self::WATCHLIST_SCREENING,
            $result->getWatchlistScreeningChecks()[0]->getType()
        );
        $this->assertEquals(
            self::WATCHLIST_ADVANCED_CA,
            $result->getWatchlistAdvancedCaChecks()[0]->getType()
        );
        $this->assertEquals(
            self::SUPPLEMENTARY_DOCUMENT_TEXT_DATA_CHECK,
            $result->getSupplementaryDocumentTextDataChecks()[0]->getType()
        );
        $this->assertEquals(
            self::LIVENESS,
            $result->getLivenessChecks()[0]->getType()
        );

        $this->assertInstanceOf(
            ThirdPartyIdentityFraudOneCheckResponse::class,
            $result->getThirdPartyIdentityFraudOneChecks()[0]
        );

        $this->assertEquals(
            self::FACE_COMPARISON,
            $result->getFaceComparisonChecks()[0]->getType()
        );
    }

    /**
     * @test
     * @covers ::getThirdPartyIdentityFraudOneChecks
     * @covers ::createCheckFromArray
     * @covers ::filterCheckByType
     */
    public function thirdPartyIdentityFraudOneChecksShouldReturnEmptyCollectionWhenNoneOfTypeArePresent()
    {
        $input = [
            'checks' => [
                ['type' => self::ID_DOCUMENT_AUTHENTICITY],
            ],
        ];

        $result = new GetSessionResult($input);

        $this->assertCount(0, $result->getThirdPartyIdentityFraudOneChecks());
    }

    /**
     * @test
     * @covers ::getIdentityProfilePreview
     * @covers ::__construct
     */
    public function shouldParseIdentityProfilePreviewResponse()
    {
        $input = [
            'identity_profile_preview' => [
                'media' => [
                    'id' => 'SOME_ID',
                    'type' => 'JSON',
                    'created' => '2021-06-11T11:39:24Z',
                    'last_updated' => '2021-06-11T11:39:24Z',
                ]
            ],
        ];

        $result = new GetSessionResult($input);

        $this->assertInstanceOf(IdentityProfilePreviewResponse::class, $result->getIdentityProfilePreview());
    }

    /**
     * @test
     * @covers ::getImportToken
     * @covers ::__construct
     */
    public function shouldParseImportTokenResponse()
    {
        $input = [
            'import_token' => [
                'media' => [
                    'id' => 'SOME_ID',
                    'type' => 'JSON',
                    'created' => '2021-06-11T11:39:24Z',
                    'last_updated' => '2021-06-11T11:39:24Z',
                ],
                'failure_reason' => 'SOME_REASON'
            ],
        ];

        $result = new GetSessionResult($input);

        $this->assertInstanceOf(ImportTokenResponse::class, $result->getImportToken());
        $this->assertInstanceOf(MediaResponse::class, $result->getImportToken()->getMedia());
        $this->assertEquals('SOME_REASON', $result->getImportToken()->getFailureReason());
    }
}
