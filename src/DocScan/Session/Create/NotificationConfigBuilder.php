<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

class NotificationConfigBuilder
{

    private const RESOURCE_UPDATE = 'RESOURCE_UPDATE';
    private const TASK_COMPLETION = 'TASK_COMPLETION';
    private const CHECK_COMPLETION = 'CHECK_COMPLETION';
    private const SESSION_COMPLETION = 'SESSION_COMPLETION';

    /**
     * @var string
     */
    private $authToken;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var string[]
     */
    private $topics = [];

    public function withAuthToken(string $authToken): self
    {
        $this->authToken = $authToken;
        return $this;
    }

    public function withEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    public function forResourceUpdate(): self
    {
        return $this->withTopic(self::RESOURCE_UPDATE);
    }

    public function withTopic(string $topic): self
    {
        $this->topics[] = $topic;
        return $this;
    }

    public function forTaskCompletion(): self
    {
        return $this->withTopic(self::TASK_COMPLETION);
    }

    public function forCheckCompletion(): self
    {
        return $this->withTopic(self::CHECK_COMPLETION);
    }

    public function forSessionCompletion(): self
    {
        return $this->withTopic(self::SESSION_COMPLETION);
    }

    public function build(): NotificationConfig
    {
        return new NotificationConfig($this->authToken, $this->endpoint, $this->topics);
    }
}
