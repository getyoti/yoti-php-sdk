<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

use Yoti\Util\Json;

class NotificationConfig implements \JsonSerializable
{
    /**
     * @var string|null
     */
    private $authToken;

    /**
     * @var string|null
     */
    private $authType;

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
     * @param string|null $authType
     */
    public function __construct(?string $authToken, ?string $endpoint, array $topics = [], ?string $authType = '')
    {
        $this->authToken = $authToken;
        $this->authType = $authType;
        $this->endpoint = $endpoint;
        $this->topics = array_unique($topics);
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        return (object) Json::withoutNullValues([
            'auth_token' => $this->getAuthToken(),
            'auth_type' => $this->getAuthType(),
            'endpoint' => $this->getEndpoint(),
            'topics' => $this->getTopics(),
        ]);
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
    public function getAuthType(): ?string
    {
        return $this->authType;
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
