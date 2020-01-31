<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

use JsonSerializable;

class NotificationConfig implements JsonSerializable
{

    /**
     * @var string|null
     */
    private $authToken;

    /**
     * @var string|null
     */
    private $endpoint;

    /**
     * @var string[]
     */
    private $topics;

    /**
     * NotificationConfig constructor.
     * @param string|null $authToken
     * @param string|null $endpoint
     * @param array<string> $topics
     */
    public function __construct(?string $authToken, ?string $endpoint, array $topics = [])
    {
        $this->authToken = $authToken;
        $this->endpoint = $endpoint;
        $this->topics = array_unique($topics);
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'auth_token' => $this->getAuthToken(),
            'endpoint' => $this->getEndpoint(),
            'topics' => $this->getTopics(),
        ];
    }

    /**
     * @return string|null
     */
    public function getAuthToken(): ?string
    {
        return $this->authToken;
    }

    /**
     * @return string|null
     */
    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    /**
     * @return string[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }
}
