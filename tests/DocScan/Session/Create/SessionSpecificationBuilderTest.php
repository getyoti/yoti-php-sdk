<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Create;

use Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheck;
use Yoti\DocScan\Session\Create\Check\RequestedLivenessCheck;
use Yoti\DocScan\Session\Create\NotificationConfig;
use Yoti\DocScan\Session\Create\SdkConfig;
use Yoti\DocScan\Session\Create\SessionSpecificationBuilder;
use Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTask;
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
     * @test
     * @covers ::build
     * @covers ::withClientSessionTokenTtl
     * @covers ::withResourcesTtl
     * @covers ::withUserTrackingId
     * @covers ::withNotifications
     * @covers ::withRequestedCheck
     * @covers ::withRequestedTask
     * @covers ::withSdkConfig
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::__construct
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::getClientSessionTokenTtl
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::getResourcesTtl
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::getUserTrackingId
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::getNotifications
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::getRequestedChecks
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::getRequestedTasks
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::getSdkConfig
     */
    public function shouldCorrectlyBuildSessionSpecification()
    {
        $sdkConfigMock = $this->createMock(SdkConfig::class);
        $notificationsMock = $this->createMock(NotificationConfig::class);
        $requestedCheckMock = $this->createMock(RequestedDocumentAuthenticityCheck::class);
        $requestedTaskMock = $this->createMock(RequestedTextExtractionTask::class);


        $sessionSpecification = (new SessionSpecificationBuilder())
            ->withClientSessionTokenTtl(self::SOME_CLIENT_SESSION_TOKEN_TTL)
            ->withResourcesTtl(self::SOME_RESOURCES_TTL)
            ->withNotifications($notificationsMock)
            ->withUserTrackingId(self::SOME_USER_TRACKING_ID)
            ->withRequestedCheck($requestedCheckMock)
            ->withRequestedTask($requestedTaskMock)
            ->withSdkConfig($sdkConfigMock)
            ->build();

        $this->assertEquals(self::SOME_CLIENT_SESSION_TOKEN_TTL, $sessionSpecification->getClientSessionTokenTtl());
        $this->assertEquals(self::SOME_RESOURCES_TTL, $sessionSpecification->getResourcesTtl());
        $this->assertEquals(self::SOME_USER_TRACKING_ID, $sessionSpecification->getUserTrackingId());
        $this->assertEquals($notificationsMock, $sessionSpecification->getNotifications());

        $this->assertCount(1, $sessionSpecification->getRequestedChecks());
        $this->assertEquals($requestedCheckMock, $sessionSpecification->getRequestedChecks()[0]);

        $this->assertCount(1, $sessionSpecification->getRequestedTasks());
        $this->assertEquals($requestedTaskMock, $sessionSpecification->getRequestedTasks()[0]);

        $this->assertEquals($sdkConfigMock, $sessionSpecification->getSdkConfig());
    }

    /**
     * @test
     * @covers ::withRequestedChecks
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::getRequestedChecks
     */
    public function shouldOverwriteCurrentListWithRequestedChecks()
    {
        $firstRequestedCheckMock = $this->createMock(RequestedDocumentAuthenticityCheck::class);
        $secondRequestedCheckMock = $this->createMock(RequestedLivenessCheck::class);

        $checkList = [ $secondRequestedCheckMock ];

        $sessionSpecification = (new SessionSpecificationBuilder())
            ->withClientSessionTokenTtl(self::SOME_CLIENT_SESSION_TOKEN_TTL)
            ->withResourcesTtl(self::SOME_RESOURCES_TTL)
            ->withUserTrackingId(self::SOME_USER_TRACKING_ID)
            ->withRequestedCheck($firstRequestedCheckMock)
            ->withRequestedChecks($checkList)
            ->build();

        $this->assertCount(1, $sessionSpecification->getRequestedChecks());
        $this->assertInstanceOf(RequestedLivenessCheck::class, $sessionSpecification->getRequestedChecks()[0]);
        $this->assertEquals($secondRequestedCheckMock, $sessionSpecification->getRequestedChecks()[0]);
    }

    /**
     * @test
     * @covers ::withRequestedTask
     * @covers ::withRequestedTasks
     */
    public function shouldOverwriteCurrentListWithRequestedTasks()
    {
        $firstRequestedTaskMock = $this->createMock(RequestedTextExtractionTask::class);
        $secondRequestedTaskMock = $this->createMock(RequestedTextExtractionTask::class);

        $checkList = [ $secondRequestedTaskMock ];

        $sessionSpecification = (new SessionSpecificationBuilder())
            ->withClientSessionTokenTtl(self::SOME_CLIENT_SESSION_TOKEN_TTL)
            ->withResourcesTtl(self::SOME_RESOURCES_TTL)
            ->withUserTrackingId(self::SOME_USER_TRACKING_ID)
            ->withRequestedTask($firstRequestedTaskMock)
            ->withRequestedTasks($checkList)
            ->build();

        $this->assertCount(1, $sessionSpecification->getRequestedTasks());
        $this->assertEquals($secondRequestedTaskMock, $sessionSpecification->getRequestedTasks()[0]);
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\SessionSpecification::jsonSerialize
     */
    public function shouldReturnCorrectJsonString()
    {
        $sdkConfigMock = $this->createMock(SdkConfig::class);
        $notificationsMock = $this->createMock(NotificationConfig::class);
        $requestedCheckMock = $this->createMock(RequestedDocumentAuthenticityCheck::class);
        $requestedTaskMock = $this->createMock(RequestedTextExtractionTask::class);

        $sdkConfigMock->method('jsonSerialize')->willReturn(['sdkConfig']);
        $notificationsMock->method('jsonSerialize')->willReturn(['notifications']);
        $requestedCheckMock->method('jsonSerialize')->willReturn(['requestedChecks']);
        $requestedTaskMock->method('jsonSerialize')->willReturn(['requestedTasks']);

        $sessionSpecification = (new SessionSpecificationBuilder())
            ->withClientSessionTokenTtl(self::SOME_CLIENT_SESSION_TOKEN_TTL)
            ->withResourcesTtl(self::SOME_RESOURCES_TTL)
            ->withNotifications($notificationsMock)
            ->withUserTrackingId(self::SOME_USER_TRACKING_ID)
            ->withRequestedCheck($requestedCheckMock)
            ->withRequestedTask($requestedTaskMock)
            ->withSdkConfig($sdkConfigMock)
            ->build();

        $expected = [
            'client_session_token_ttl' => self::SOME_CLIENT_SESSION_TOKEN_TTL,
            'resources_ttl' => self::SOME_RESOURCES_TTL,
            'user_tracking_id' => self::SOME_USER_TRACKING_ID,
            'notifications' => [ 'notifications' ],
            'requested_checks' => [ [ 'requestedChecks' ] ],
            'requested_tasks' => [ [ 'requestedTasks' ] ],
            'sdk_config' => [ 'sdkConfig' ]
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($sessionSpecification));
    }
}
