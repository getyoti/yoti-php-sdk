<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\AuthenticityCheckResponse;
use Yoti\DocScan\Session\Retrieve\CheckResponse;
use Yoti\DocScan\Session\Retrieve\GetSessionResult;
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
    private const SUPPLEMENTARY_DOCUMENT_TEXT_DATA_CHECK = 'SUPPLEMENTARY_DOCUMENT_TEXT_DATA_CHECK';
    private const LIVENESS = 'LIVENESS';
    private const SOME_UNKNOWN_TYPE = 'someUnknownType';
    private const SOME_STATE = 'someState';
    private const SOME_SESSION_ID = 'someSessionId';
    private const SOME_USER_TRACKING_ID = 'someUserTrackingId';
    private const SOME_CLIENT_SESSION_TOKEN = 'someClientSessionToken';
    private const SOME_CLIENT_SESSION_TOKEN_TTL = 300;
    private const SOME_BIOMETRIC_CONSENT_DATE_STRING = '2019-12-02T12:00:00.123Z';

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
                [ 'type' => self::ID_DOCUMENT_AUTHENTICITY ]
            ],
            'resources' => [ ],
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
                [ 'type' => self::SOME_UNKNOWN_TYPE ],
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
     * @covers ::getSupplementaryDocumentTextDataChecks
     * @covers ::getLivenessChecks
     * @covers ::createCheckFromArray
     * @covers ::filterCheckByType
     */
    public function shouldFilterChecks(): void
    {
        $input = [
            'checks' => [
                [ 'type' => self::ID_DOCUMENT_AUTHENTICITY ],
                [ 'type' => self::ID_DOCUMENT_FACE_MATCH ],
                [ 'type' => self::ID_DOCUMENT_TEXT_DATA_CHECK ],
                [ 'type' => self::ID_DOCUMENT_COMPARISON ],
                [ 'type' => self::SUPPLEMENTARY_DOCUMENT_TEXT_DATA_CHECK ],
                [ 'type' => self::LIVENESS ],
            ],
        ];

        $result = new GetSessionResult($input);

        $this->assertCount(6, $result->getChecks());
        $this->assertCount(1, $result->getAuthenticityChecks());
        $this->assertCount(1, $result->getFaceMatchChecks());
        $this->assertCount(1, $result->getTextDataChecks());
        $this->assertCount(1, $result->getIdDocumentTextDataChecks());
        $this->assertCount(1, $result->getIdDocumentComparisonChecks());
        $this->assertCount(1, $result->getSupplementaryDocumentTextDataChecks());
        $this->assertCount(1, $result->getLivenessChecks());

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
            self::SUPPLEMENTARY_DOCUMENT_TEXT_DATA_CHECK,
            $result->getSupplementaryDocumentTextDataChecks()[0]->getType()
        );
        $this->assertEquals(
            self::LIVENESS,
            $result->getLivenessChecks()[0]->getType()
        );
    }
}
