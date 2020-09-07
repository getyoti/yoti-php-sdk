<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

use Yoti\DocScan\Session\Create\Check\RequestedCheck;
use Yoti\DocScan\Session\Create\Filters\RequiredDocument;
use Yoti\DocScan\Session\Create\Task\RequestedTask;

class SessionSpecificationBuilder
{

    /**
     * @var int
     */
    private $clientSessionTokenTtl;

    /**
     * @var int
     */
    private $resourcesTtl;

    /**
     * @var string
     */
    private $userTrackingId;

    /**
     * @var NotificationConfig
     */
    private $notifications;

    /**
     * @var RequestedCheck[]
     */
    private $requestedChecks = [];

    /**
     * @var RequestedTask[]
     */
    private $requestedTasks = [];

    /**
     * @var SdkConfig
     */
    private $sdkConfig;

    /**
     * @var RequiredDocument[]
     */
    private $requiredDocuments = [];

    /**
     * @var bool|null
     */
    private $blockBiometricConsent;

    /**
     * @param int $clientSessionTokenTtl
     * @return $this
     */
    public function withClientSessionTokenTtl(int $clientSessionTokenTtl): self
    {
        $this->clientSessionTokenTtl = $clientSessionTokenTtl;
        return $this;
    }

    /**
     * @param int $resourcesTtl
     * @return $this
     */
    public function withResourcesTtl(int $resourcesTtl): self
    {
        $this->resourcesTtl = $resourcesTtl;
        return $this;
    }

    /**
     * @param string $userTrackingId
     * @return $this
     */
    public function withUserTrackingId(string $userTrackingId): self
    {
        $this->userTrackingId = $userTrackingId;
        return $this;
    }

    /**
     * @param NotificationConfig $notificationConfig
     * @return $this
     */
    public function withNotifications(NotificationConfig $notificationConfig): self
    {
        $this->notifications = $notificationConfig;
        return $this;
    }

    /**
     * @param RequestedCheck $requestedCheck
     * @return $this
     */
    public function withRequestedCheck(RequestedCheck $requestedCheck): self
    {
        $this->requestedChecks[] = $requestedCheck;
        return $this;
    }

    /**
     * @param RequestedCheck[] $requestedChecks
     * @return $this
     */
    public function withRequestedChecks(array $requestedChecks): self
    {
        $this->requestedChecks = $requestedChecks;
        return $this;
    }

    /**
     * @param RequestedTask $requestedTask
     * @return $this
     */
    public function withRequestedTask(RequestedTask $requestedTask): self
    {
        $this->requestedTasks[] = $requestedTask;
        return $this;
    }

    /**
     * @param RequestedTask[] $requestedTasks
     * @return $this
     */
    public function withRequestedTasks(array $requestedTasks): self
    {
        $this->requestedTasks = $requestedTasks;
        return $this;
    }

    /**
     * @param SdkConfig $sdkConfig
     * @return $this
     */
    public function withSdkConfig(SdkConfig $sdkConfig): self
    {
        $this->sdkConfig = $sdkConfig;
        return $this;
    }

    /**
     * Adds a RequiredDocument to the list documents required from the client
     *
     * @param RequiredDocument $requiredDocument
     *
     * @return $this
     */
    public function withRequiredDocument(RequiredDocument $requiredDocument): self
    {
        $this->requiredDocuments[] = $requiredDocument;
        return $this;
    }

    /**
     * Sets whether or not to block the collection of biometric consent
     *
     * @param bool $blockBiometricConsent
     *
     * @return $this
     */
    public function withBlockBiometricConsent(bool $blockBiometricConsent): self
    {
        $this->blockBiometricConsent = $blockBiometricConsent;
        return $this;
    }

    /**
     * @return SessionSpecification
     */
    public function build(): SessionSpecification
    {
        return new SessionSpecification(
            $this->clientSessionTokenTtl,
            $this->resourcesTtl,
            $this->userTrackingId,
            $this->notifications,
            $this->requestedChecks,
            $this->requestedTasks,
            $this->sdkConfig,
            $this->requiredDocuments,
            $this->blockBiometricConsent
        );
    }
}
