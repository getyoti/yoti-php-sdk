<?php

namespace Yoti\Test\DocScan\Session\Retrieve\Configuration;

use Yoti\DocScan\Session\Retrieve\Configuration\Capture\CaptureResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\SessionConfigurationResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\Configuration\SessionConfigurationResponse
 */
class SessionConfigurationResponseTest extends TestCase
{
    private const SOME_CLIENT_SESSION_TTL = 12345678;
    private const SOME_SESSION_ID = 'SOME_SESSION_ID';
    private const SOME_REQUESTED_CHECKS = ['SOME_CHECK', 'SOME_ANOTHER_CHECK'];
    private const SOME_CAPTURE = [
        'biometric_consent' => 'SOME_STRING',
        'required_resources' => [
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
        ]
    ];

    /**
     * @test
     * @covers ::__construct
     * @covers ::getClientSessionTokenTtl
     * @covers ::getSessionId
     * @covers ::getRequestedChecks
     * @covers ::getCapture
     */
    public function shouldBuildCorrectly()
    {
        $sessionData = [
            'client_session_token_ttl' => self::SOME_CLIENT_SESSION_TTL,
            'session_id' => self::SOME_SESSION_ID,
            'requested_checks' => self::SOME_REQUESTED_CHECKS,
            'capture' => self::SOME_CAPTURE,
        ];

        $result = new SessionConfigurationResponse($sessionData);


        $this->assertEquals(self::SOME_CLIENT_SESSION_TTL, $result->getClientSessionTokenTtl());
        $this->assertEquals(self::SOME_SESSION_ID, $result->getSessionId());
        $this->assertEquals(self::SOME_REQUESTED_CHECKS, $result->getRequestedChecks());

        $this->assertInstanceOf(CaptureResponse::class, $result->getCapture());

        $this->assertCount(2, $result->getRequestedChecks());
    }
}
