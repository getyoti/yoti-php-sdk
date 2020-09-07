<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Create;

use Yoti\DocScan\Session\Create\Check\RequestedCheck;
use Yoti\DocScan\Session\Create\Filters\RequiredDocument;
use Yoti\DocScan\Session\Create\NotificationConfig;
use Yoti\DocScan\Session\Create\SdkConfig;
use Yoti\DocScan\Session\Create\SessionSpecificationBuilder;
use Yoti\DocScan\Session\Create\Task\RequestedTask;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\SessionSpecificationBuilder
 */
class SessionSpecificationBuilderTest extends TestCase
{
    private const SOME_CLIENT_SESSION_TOKEN_TTL = 30;
    private const SOME_RESOURCES_TTL = 65000;
    private const SOME_USER_TRACKING_ID = 'someUserTrackingId';

    /**
     * @var SdkConfig
     */
    private $sdkConfigMock;

    /**
     * @var NotificationConfig
     */
    private $notificationsMock;

    /**
     * @var RequestedCheck
     */
    private $requestedCheckMock;

    /**
     * @var RequestedTask
     */
    private $requestedTaskMock;

    /**
     * @var RequiredDocument
     */
    private $requiredDocumentMock;

    public function setup(): void
    {
        $this->sdkConfigMock = $this->createMock(SdkConfig::class);
        $this->sdkConfigMock->method('jsonSerialize')->willReturn(['sdkConfig']);

        $this->notificationsMock = $this->createMock(NotificationConfig::class);
        $this->notificationsMock->method('jsonSerialize')->willReturn(['notifications']);

        $this->requestedCheckMock = $this->createMock(RequestedCheck::class);
        $this->requestedCheckMock->method('jsonSerialize')->willReturn(['requestedChecks']);

        $this->requestedTaskMock = $this->createMock(RequestedTask::class);
        $this->requestedTaskMock->method('jsonSerialize')->willReturn(['requestedTasks']);

        $this->requiredDocumentMock = $this->createMock(RequiredDocument::class);
        $this->requiredDocumentMock->method('jsonSerialize')->willReturn((object) ['requiredDocument']);
    }

    /**
     * @test
     * @covers ::build
     * @covers ::withClientSessionTokenTtl
     * @covers ::withResourcesTtl
     * @covers ::withUserTrackingId
     * @covers ::withNotifications
     * @covers ::withRequestedCheck
     * @covers ::withRequestedTask
     * @covers ::withSdkConfig
     * @covers ::withRequiredDocument
     * @covers ::withBlockBiometricConsent
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::__construct
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::getClientSessionTokenTtl
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::getResourcesTtl
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::getUserTrackingId
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::getNotifications
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::getRequestedChecks
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::getRequestedTasks
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::getSdkConfig
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::getRequiredDocuments
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::getBlockBiometricConsent
     */
    public function shouldCorrectlyBuildSessionSpecification()
    {
        $sessionSpecification = (new SessionSpecificationBuilder())
            ->withClientSessionTokenTtl(self::SOME_CLIENT_SESSION_TOKEN_TTL)
            ->withResourcesTtl(self::SOME_RESOURCES_TTL)
            ->withNotifications($this->notificationsMock)
            ->withUserTrackingId(self::SOME_USER_TRACKING_ID)
            ->withRequestedCheck($this->requestedCheckMock)
            ->withRequestedTask($this->requestedTaskMock)
            ->withSdkConfig($this->sdkConfigMock)
            ->withRequiredDocument($this->requiredDocumentMock)
            ->withBlockBiometricConsent(true)
            ->build();

        $this->assertEquals(self::SOME_CLIENT_SESSION_TOKEN_TTL, $sessionSpecification->getClientSessionTokenTtl());
        $this->assertEquals(self::SOME_RESOURCES_TTL, $sessionSpecification->getResourcesTtl());
        $this->assertEquals(self::SOME_USER_TRACKING_ID, $sessionSpecification->getUserTrackingId());
        $this->assertEquals($this->notificationsMock, $sessionSpecification->getNotifications());

        $this->assertCount(1, $sessionSpecification->getRequestedChecks());
        $this->assertEquals($this->requestedCheckMock, $sessionSpecification->getRequestedChecks()[0]);

        $this->assertCount(1, $sessionSpecification->getRequestedTasks());
        $this->assertEquals($this->requestedTaskMock, $sessionSpecification->getRequestedTasks()[0]);

        $this->assertEquals($this->sdkConfigMock, $sessionSpecification->getSdkConfig());

        $this->assertCount(1, $sessionSpecification->getRequiredDocuments());
        $this->assertEquals($this->requiredDocumentMock, $sessionSpecification->getRequiredDocuments()[0]);

        $this->assertTrue($sessionSpecification->getBlockBiometricConsent());
    }

    /**
     * @test
     * @covers ::withRequestedChecks
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::getRequestedChecks
     */
    public function shouldOverwriteCurrentListWithRequestedChecks()
    {
        $someOtherRequestedCheckMock = $this->createMock(RequestedCheck::class);

        $sessionSpecification = (new SessionSpecificationBuilder())
            ->withClientSessionTokenTtl(self::SOME_CLIENT_SESSION_TOKEN_TTL)
            ->withResourcesTtl(self::SOME_RESOURCES_TTL)
            ->withUserTrackingId(self::SOME_USER_TRACKING_ID)
            ->withRequestedCheck($this->requestedCheckMock)
            ->withRequestedChecks([ $someOtherRequestedCheckMock ])
            ->build();

        $this->assertCount(1, $sessionSpecification->getRequestedChecks());
        $this->assertSame($someOtherRequestedCheckMock, $sessionSpecification->getRequestedChecks()[0]);
    }

    /**
     * @test
     * @covers ::withRequestedTask
     * @covers ::withRequestedTasks
     */
    public function shouldOverwriteCurrentListWithRequestedTasks()
    {
        $someOtherRequestedTaskMock = $this->createMock(RequestedTask::class);

        $sessionSpecification = (new SessionSpecificationBuilder())
            ->withClientSessionTokenTtl(self::SOME_CLIENT_SESSION_TOKEN_TTL)
            ->withResourcesTtl(self::SOME_RESOURCES_TTL)
            ->withUserTrackingId(self::SOME_USER_TRACKING_ID)
            ->withRequestedTask($this->requestedTaskMock)
            ->withRequestedTasks([ $someOtherRequestedTaskMock ])
            ->build();

        $this->assertCount(1, $sessionSpecification->getRequestedTasks());
        $this->assertSame($someOtherRequestedTaskMock, $sessionSpecification->getRequestedTasks()[0]);
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::jsonSerialize
     */
    public function shouldReturnCorrectJsonString()
    {
        $sessionSpecification = (new SessionSpecificationBuilder())
            ->withClientSessionTokenTtl(self::SOME_CLIENT_SESSION_TOKEN_TTL)
            ->withResourcesTtl(self::SOME_RESOURCES_TTL)
            ->withNotifications($this->notificationsMock)
            ->withUserTrackingId(self::SOME_USER_TRACKING_ID)
            ->withRequestedCheck($this->requestedCheckMock)
            ->withRequestedTask($this->requestedTaskMock)
            ->withSdkConfig($this->sdkConfigMock)
            ->withRequiredDocument($this->requiredDocumentMock)
            ->withBlockBiometricConsent(true)
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'client_session_token_ttl' => self::SOME_CLIENT_SESSION_TOKEN_TTL,
                'resources_ttl' => self::SOME_RESOURCES_TTL,
                'user_tracking_id' => self::SOME_USER_TRACKING_ID,
                'notifications' => $this->notificationsMock,
                'sdk_config' => $this->sdkConfigMock,
                'requested_checks' => [ $this->requestedCheckMock ],
                'requested_tasks' => [ $this->requestedTaskMock ],
                'required_documents' => [ $this->requiredDocumentMock ],
                'block_biometric_consent' => true,
            ]),
            json_encode($sessionSpecification)
        );
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::jsonSerialize
     */
    public function shouldReturnCorrectJsonStringWithoutOptionalProperties()
    {
        $sessionSpecification = (new SessionSpecificationBuilder())->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'requested_checks' => [],
                'requested_tasks' => [],
                'required_documents' => [],
            ]),
            json_encode($sessionSpecification)
        );
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::jsonSerialize
     */
    public function shouldReturnCorrectJsonStringWithBlockBiometricConsentTrue()
    {
        $sessionSpecification = (new SessionSpecificationBuilder())
            ->withBlockBiometricConsent(true)
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'requested_checks' => [],
                'requested_tasks' => [],
                'required_documents' => [],
                'block_biometric_consent' => true,
            ]),
            json_encode($sessionSpecification)
        );
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::jsonSerialize
     */
    public function shouldReturnCorrectJsonStringWithBlockBiometricConsentFalse()
    {
        $sessionSpecification = (new SessionSpecificationBuilder())
            ->withBlockBiometricConsent(false)
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'requested_checks' => [],
                'requested_tasks' => [],
                'required_documents' => [],
                'block_biometric_consent' => false,
            ]),
            json_encode($sessionSpecification)
        );
    }
}
