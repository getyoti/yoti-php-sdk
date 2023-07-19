<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

use JsonSerializable;
use stdClass;
use Yoti\DocScan\Session\Create\Check\RequestedCheck;
use Yoti\DocScan\Session\Create\Filters\RequiredDocument;
use Yoti\DocScan\Session\Create\Task\RequestedTask;
use Yoti\Util\Json;

class SessionSpecification implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $clientSessionTokenTtl;

    /**
     * @var string|null
     */
    private $sessionDeadline;

    /**
     * @var int|null
     */
    private $resourcesTtl;

    /**
     * @var string|null
     */
    private $userTrackingId;

    /**
     * @var NotificationConfig|null
     */
    private $notifications;

    /**
     * @var RequestedCheck[]
     */
    private $requestedChecks;

    /**
     * @var RequestedTask[]
     */
    private $requestedTasks;

    /**
     * @var RequiredDocument[]
     */
    private $requiredDocuments;

    /**
     * @var SdkConfig|null
     */
    private $sdkConfig;

    /**
     * @var bool|null
     */
    private $blockBiometricConsent;

    /**
     * @var IbvOptions|null
     */
    private $ibvOptions;

    /**
     * @var object|null
     */
    private $subject;

    /**
     * @var object|null
     */
    private $identityProfileRequirements;

    private ?bool $createIdentityProfilePreview;

    /**
     * @var ImportToken|null
     */
    private $importToken;

    /**
     * @param int|null $clientSessionTokenTtl
     * @param string|null $sessionDeadline
     * @param int|null $resourcesTtl
     * @param string|null $userTrackingId
     * @param NotificationConfig|null $notificationConfig
     * @param RequestedCheck[] $requestedChecks
     * @param RequestedTask[] $requestedTasks
     * @param SdkConfig|null $sdkConfig
     * @param RequiredDocument[] $requiredDocuments
     * @param bool|null $blockBiometricConsent
     * @param IbvOptions|null $ibvOptions
     * @param object|null $subject
     * @param object|null $identityProfileRequirements
     * @param bool|null $createIdentityProfilePreview
     * @param ImportToken|null $importToken
     */
    public function __construct(
        ?int $clientSessionTokenTtl,
        ?string $sessionDeadline,
        ?int $resourcesTtl,
        ?string $userTrackingId,
        ?NotificationConfig $notificationConfig,
        array $requestedChecks,
        array $requestedTasks,
        ?SdkConfig $sdkConfig,
        array $requiredDocuments = [],
        ?bool $blockBiometricConsent = null,
        ?IbvOptions $ibvOptions = null,
        ?object $subject = null,
        ?object $identityProfileRequirements = null,
        ?bool $createIdentityProfilePreview = null,
        ?ImportToken $importToken = null
    ) {
        $this->clientSessionTokenTtl = $clientSessionTokenTtl;
        $this->sessionDeadline = $sessionDeadline;
        $this->resourcesTtl = $resourcesTtl;
        $this->userTrackingId = $userTrackingId;
        $this->notifications = $notificationConfig;
        $this->requestedChecks = $requestedChecks;
        $this->requestedTasks = $requestedTasks;
        $this->sdkConfig = $sdkConfig;
        $this->requiredDocuments = $requiredDocuments;
        $this->blockBiometricConsent = $blockBiometricConsent;
        $this->ibvOptions = $ibvOptions;
        $this->subject = $subject;
        $this->identityProfileRequirements = $identityProfileRequirements;
        $this->createIdentityProfilePreview = $createIdentityProfilePreview;
        $this->importToken = $importToken;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        return (object) Json::withoutNullValues([
            'client_session_token_ttl' => $this->getClientSessionTokenTtl(),
            'session_deadline' => $this->getSessionDeadline(),
            'resources_ttl' => $this->getResourcesTtl(),
            'user_tracking_id' => $this->getUserTrackingId(),
            'notifications' => $this->getNotifications(),
            'requested_checks' => $this->getRequestedChecks(),
            'requested_tasks' => $this->getRequestedTasks(),
            'sdk_config' => $this->getSdkConfig(),
            'required_documents' => $this->getRequiredDocuments(),
            'block_biometric_consent' => $this->getBlockBiometricConsent(),
            'ibv_options' => $this->getIbvOptions(),
            'subject' => $this->getSubject(),
            'identity_profile_requirements' => $this->getIdentityProfileRequirements(),
            'create_identity_profile_preview' => $this->getCreateIdentityProfilePreview(),
            'import_token' => $this->getImportToken(),
        ]);
    }

    /**
     * @return int|null
     */
    public function getClientSessionTokenTtl(): ?int
    {
        return $this->clientSessionTokenTtl;
    }

    /**
     * @return string|null
     */
    public function getSessionDeadline(): ?string
    {
        return $this->sessionDeadline;
    }

    /**
     * @return int|null
     */
    public function getResourcesTtl(): ?int
    {
        return $this->resourcesTtl;
    }

    /**
     * @return string|null
     */
    public function getUserTrackingId(): ?string
    {
        return $this->userTrackingId;
    }

    /**
     * @return NotificationConfig|null
     */
    public function getNotifications(): ?NotificationConfig
    {
        return $this->notifications;
    }

    /**
     * @return RequestedCheck[]
     */
    public function getRequestedChecks(): array
    {
        return $this->requestedChecks;
    }

    /**
     * @return RequestedTask[]
     */
    public function getRequestedTasks(): array
    {
        return $this->requestedTasks;
    }

    /**
     * @return SdkConfig|null
     */
    public function getSdkConfig(): ?SdkConfig
    {
        return $this->sdkConfig;
    }

    /**
     * @return RequiredDocument[]
     */
    public function getRequiredDocuments(): array
    {
        return $this->requiredDocuments;
    }

    /**
     * Whether or not to block the collection of biometric consent
     *
     * @return bool|null
     */
    public function getBlockBiometricConsent(): ?bool
    {
        return $this->blockBiometricConsent;
    }

    /**
     * The options that define if a session will be required to be performed
     * using In-Branch Verification
     *
     * @return IbvOptions|null
     */
    public function getIbvOptions(): ?IbvOptions
    {
        return $this->ibvOptions;
    }

    /**
     * @return object|null
     */
    public function getSubject(): ?object
    {
        return $this->subject;
    }

    /**
     * @return object|null
     */
    public function getIdentityProfileRequirements(): ?object
    {
        return $this->identityProfileRequirements;
    }

    public function getCreateIdentityProfilePreview(): ?bool
    {
        return $this->createIdentityProfilePreview;
    }

    public function getImportToken(): ?ImportToken
    {
        return $this->importToken;
    }
}
