<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

use JsonSerializable;
use Yoti\DocScan\Session\Create\Check\RequestedCheck;
use Yoti\DocScan\Session\Create\Task\RequestedTask;

class SessionSpecification implements JsonSerializable
{

    /**
     * @var int|null
     */
    private $clientSessionTokenTtl;

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
     * @var SdkConfig|null
     */
    private $sdkConfig;

    /**
     * SessionSpecification constructor.
     * @param int|null $clientSessionTokenTtl
     * @param int|null $resourcesTtl
     * @param string|null $userTrackingId
     * @param NotificationConfig|null $notificationConfig
     * @param RequestedCheck[] $requestedChecks
     * @param RequestedTask[] $requestedTasks
     * @param SdkConfig|null $sdkConfig
     */
    public function __construct(
        ?int $clientSessionTokenTtl,
        ?int $resourcesTtl,
        ?string $userTrackingId,
        ?NotificationConfig $notificationConfig,
        array $requestedChecks,
        array $requestedTasks,
        ?SdkConfig $sdkConfig
    ) {
        $this->clientSessionTokenTtl = $clientSessionTokenTtl;
        $this->resourcesTtl = $resourcesTtl;
        $this->userTrackingId = $userTrackingId;
        $this->notifications = $notificationConfig;
        $this->requestedChecks = $requestedChecks;
        $this->requestedTasks = $requestedTasks;
        $this->sdkConfig = $sdkConfig;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'client_session_token_ttl' => $this->getClientSessionTokenTtl(),
            'resources_ttl' => $this->getResourcesTtl(),
            'user_tracking_id' => $this->getUserTrackingId(),
            'notifications' => $this->getNotifications(),
            'requested_checks' => $this->getRequestedChecks(),
            'requested_tasks' => $this->getRequestedTasks(),
            'sdk_config' => $this->getSdkConfig(),
        ];
    }

    /**
     * @return int|null
     */
    public function getClientSessionTokenTtl(): ?int
    {
        return $this->clientSessionTokenTtl;
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
}
