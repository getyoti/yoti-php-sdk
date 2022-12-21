<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

use Yoti\DocScan\Constants;

class NotificationConfigBuilder
{
    private const RESOURCE_UPDATE = 'RESOURCE_UPDATE';
    private const TASK_COMPLETION = 'TASK_COMPLETION';
    private const CHECK_COMPLETION = 'CHECK_COMPLETION';
    private const SESSION_COMPLETION = 'SESSION_COMPLETION';
    private const CLIENT_SESSION_TOKEN_DELETED = 'CLIENT_SESSION_TOKEN_DELETED';

    /**
     * @var string
     */
    private $authToken;

    /**
     * @var string
     */
    private $authType;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var string[]
     */
    private $topics = [];

    /**
     * @param string $authToken
     * @return $this
     */
    public function withAuthToken(string $authToken): self
    {
        $this->authToken = $authToken;
        return $this;
    }

    /**
     * @return $this
     */
    public function withAuthTypeBasic(): self
    {
        $this->authType = Constants::BASIC;
        return $this;
    }

    /**
     * @return $this
     */
    public function withAuthTypeBearer(): self
    {
        $this->authType = Constants::BEARER;
        return $this;
    }

    /**
     * @param string $endpoint
     * @return $this
     */
    public function withEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @return $this
     */
    public function forResourceUpdate(): self
    {
        return $this->withTopic(self::RESOURCE_UPDATE);
    }

    /**
     * @param string $topic
     * @return $this
     */
    public function withTopic(string $topic): self
    {
        $this->topics[] = $topic;
        return $this;
    }

    /**
     * @return $this
     */
    public function forTaskCompletion(): self
    {
        return $this->withTopic(self::TASK_COMPLETION);
    }

    /**
     * @return $this
     */
    public function forCheckCompletion(): self
    {
        return $this->withTopic(self::CHECK_COMPLETION);
    }

    /**
     * @return $this
     */
    public function forSessionCompletion(): self
    {
        return $this->withTopic(self::SESSION_COMPLETION);
    }

    /**
     * @return $this
     */
    public function forClientSessionCompletion(): self
    {
        return $this->withTopic(self::CLIENT_SESSION_TOKEN_DELETED);
    }

    /**
     * @return NotificationConfig
     */
    public function build(): NotificationConfig
    {
        return new NotificationConfig($this->authToken, $this->endpoint, $this->topics, $this->authType);
    }
}
