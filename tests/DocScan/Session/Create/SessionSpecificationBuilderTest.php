<?php

declare(strict_types=1);

namespace Yoti\Test\IDV\Session\Create;

use Yoti\IDV\Session\Create\Check\RequestedCheck;
use Yoti\IDV\Session\Create\Filters\RequiredDocument;
use Yoti\IDV\Session\Create\IbvOptions;
use Yoti\IDV\Session\Create\NotificationConfig;
use Yoti\IDV\Session\Create\SdkConfig;
use Yoti\IDV\Session\Create\SessionSpecificationBuilder;
use Yoti\IDV\Session\Create\Task\RequestedTask;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Create\SessionSpecificationBuilder
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

    /**
     * @var IbvOptions
     */
    private $ibvOptionsMock;

    /**
     * @var object
     */
    private $subject;

    /**
     * @var object
     */
    private $identityProfileRequirements;

    public function setup(): void
    {
        $this->sdkConfigMock = $this->createMock(SdkConfig::class);
        $this->sdkConfigMock->method('jsonSerialize')->willReturn((object)['sdkConfig']);

        $this->notificationsMock = $this->createMock(NotificationConfig::class);
        $this->notificationsMock->method('jsonSerialize')->willReturn((object)['notifications']);

        $this->requestedCheckMock = $this->createMock(RequestedCheck::class);
        $this->requestedCheckMock->method('jsonSerialize')->willReturn((object)['requestedChecks']);

        $this->requestedTaskMock = $this->createMock(RequestedTask::class);
        $this->requestedTaskMock->method('jsonSerialize')->willReturn((object)['requestedTasks']);

        $this->requiredDocumentMock = $this->createMock(RequiredDocument::class);
        $this->requiredDocumentMock->method('jsonSerialize')->willReturn((object)['requiredDocument']);

        $this->ibvOptionsMock = $this->createMock(IbvOptions::class);

        $this->subject = (object)[1 => 'some'];

        $this->identityProfileRequirements = (object)[
            'trust_framework' => 'UK_TFIDA',
            'scheme' => [
                'type' => 'DBS',
                'objective' => 'STANDARD'
            ]
        ];
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
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::__construct
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::getClientSessionTokenTtl
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::getResourcesTtl
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::getUserTrackingId
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::getNotifications
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::getRequestedChecks
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::getRequestedTasks
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::getSdkConfig
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::getRequiredDocuments
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::getBlockBiometricConsent
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
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::getRequestedChecks
     */
    public function shouldOverwriteCurrentListWithRequestedChecks()
    {
        $someOtherRequestedCheckMock = $this->createMock(RequestedCheck::class);

        $sessionSpecification = (new SessionSpecificationBuilder())
            ->withClientSessionTokenTtl(self::SOME_CLIENT_SESSION_TOKEN_TTL)
            ->withResourcesTtl(self::SOME_RESOURCES_TTL)
            ->withUserTrackingId(self::SOME_USER_TRACKING_ID)
            ->withRequestedCheck($this->requestedCheckMock)
            ->withRequestedChecks([$someOtherRequestedCheckMock])
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
            ->withRequestedTasks([$someOtherRequestedTaskMock])
            ->build();

        $this->assertCount(1, $sessionSpecification->getRequestedTasks());
        $this->assertSame($someOtherRequestedTaskMock, $sessionSpecification->getRequestedTasks()[0]);
    }

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::jsonSerialize
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
                'requested_checks' => [$this->requestedCheckMock],
                'requested_tasks' => [$this->requestedTaskMock],
                'required_documents' => [$this->requiredDocumentMock],
                'block_biometric_consent' => true,
                'create_identity_profile_preview' => false,
            ]),
            json_encode($sessionSpecification)
        );
    }

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::jsonSerialize
     */
    public function shouldReturnCorrectJsonStringWithoutOptionalProperties()
    {
        $sessionSpecification = (new SessionSpecificationBuilder())->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'requested_checks' => [],
                'requested_tasks' => [],
                'required_documents' => [],
                'create_identity_profile_preview' => false,
            ]),
            json_encode($sessionSpecification)
        );
    }

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::jsonSerialize
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
                'create_identity_profile_preview' => false,
                'block_biometric_consent' => true,
            ]),
            json_encode($sessionSpecification)
        );
    }

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::jsonSerialize
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
                'create_identity_profile_preview' => false,
                'block_biometric_consent' => false,
            ]),
            json_encode($sessionSpecification)
        );
    }

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::getSessionDeadline
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::withSessionDeadline
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::build
     */
    public function shouldSetTheSessionDeadline()
    {
        $date = new \DateTimeImmutable();
        $correctDateFormatValue = $date->format(SessionSpecificationBuilder::DATETIME_FORMAT);

        $sessionSpecificationResult = (new SessionSpecificationBuilder())
            ->withSessionDeadline($date)
            ->build();

        $this->assertEquals($correctDateFormatValue, $sessionSpecificationResult->getSessionDeadline());
    }

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::getIbvOptions
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::__construct
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::withIbvOptions
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::build
     */
    public function withIbvOptionsShouldSetTheIbvOptions()
    {
        $sessionSpecificationResult = (new SessionSpecificationBuilder())
            ->withIbvOptions($this->ibvOptionsMock)
            ->build();

        $this->assertEquals($this->ibvOptionsMock, $sessionSpecificationResult->getIbvOptions());
    }

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::withIbvOptions
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::build
     */
    public function shouldReturnCorrectJsonStringWithIbvOptions()
    {
        $sessionSpecification = (new SessionSpecificationBuilder())
            ->withIbvOptions($this->ibvOptionsMock)
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'requested_checks' => [],
                'requested_tasks' => [],
                'required_documents' => [],
                'create_identity_profile_preview' => false,
                'ibv_options' => $this->ibvOptionsMock,
            ]),
            json_encode($sessionSpecification)
        );
    }

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::getSubject
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::__construct
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::withSubject
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::build
     */
    public function shouldBuildWithSubject()
    {
        $sessionSpecificationResult = (new SessionSpecificationBuilder())
            ->withSubject($this->subject)
            ->build();

        $this->assertEquals($this->subject, $sessionSpecificationResult->getSubject());
    }

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::getSubject
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::__construct
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::withSubject
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::build
     */
    public function shouldNotImplicitlySetAValueForSubject()
    {
        $sessionSpecificationResult = (new SessionSpecificationBuilder())
            ->build();

        $this->assertNull($sessionSpecificationResult->getSubject());
    }

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::withSubject
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::build
     */
    public function shouldReturnCorrectJsonStringWithSubject()
    {
        $sessionSpecification = (new SessionSpecificationBuilder())
            ->withSubject($this->subject)
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'requested_checks' => [],
                'requested_tasks' => [],
                'required_documents' => [],
                'create_identity_profile_preview' => false,
                'subject' => $this->subject,
            ]),
            json_encode($sessionSpecification)
        );
    }

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::getIdentityProfileRequirements
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::__construct
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::withIdentityProfileRequirements
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::build
     */
    public function shouldBuildWithIdentityProfileRequirements()
    {
        $sessionSpecificationResult = (new SessionSpecificationBuilder())
            ->withIdentityProfileRequirements($this->identityProfileRequirements)
            ->build();

        $this->assertEquals(
            $this->identityProfileRequirements,
            $sessionSpecificationResult->getIdentityProfileRequirements()
        );
    }

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::getIdentityProfileRequirements
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::__construct
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::withIdentityProfileRequirements
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::build
     */
    public function shouldNotImplicitlySetAValueForIdentityProfileRequirements()
    {
        $sessionSpecificationResult = (new SessionSpecificationBuilder())
            ->build();

        $this->assertNull($sessionSpecificationResult->getIdentityProfileRequirements());
    }

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::withIdentityProfileRequirements
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::build
     */
    public function shouldReturnCorrectJsonStringWithIdentityProfileRequirements()
    {
        $sessionSpecification = (new SessionSpecificationBuilder())
            ->withIdentityProfileRequirements($this->identityProfileRequirements)
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'requested_checks' => [],
                'requested_tasks' => [],
                'required_documents' => [],
                'create_identity_profile_preview' => false,
                'identity_profile_requirements' => $this->identityProfileRequirements,
            ]),
            json_encode($sessionSpecification)
        );
    }

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::getCreateIdentityProfilePreview
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::__construct
     * @covers \Yoti\IDV\Session\Create\SessionSpecification::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::withCreateIdentityProfilePreview
     * @covers \Yoti\IDV\Session\Create\SessionSpecificationBuilder::build
     */
    public function shouldReturnCorrectJsonStringWithIdentityProfilePreviewTrue()
    {
        $sessionSpecification = (new SessionSpecificationBuilder())
            ->withCreateIdentityProfilePreview()
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'requested_checks' => [],
                'requested_tasks' => [],
                'required_documents' => [],
                'create_identity_profile_preview' => true,
            ]),
            json_encode($sessionSpecification)
        );
    }
}
