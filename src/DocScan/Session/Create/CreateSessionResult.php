<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

class CreateSessionResult
{

    /**
     * @var int|null
     */
    private $clientSessionTokenTtl;

    /**
     * @var string|null
     */
    private $clientSessionToken;

    /**
     * @var string|null
     */
    private $sessionId;

    /**
     * CreateSessionResult constructor.
     *
     * @param array<string, mixed> $sessionResultData
     */
    public function __construct(array $sessionResultData)
    {
        $this->clientSessionTokenTtl = $sessionResultData['client_session_token_ttl'] ?? null;
        $this->clientSessionToken = $sessionResultData['client_session_token'] ?? null;
        $this->sessionId = $sessionResultData['session_id'] ?? null;
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
    public function getClientSessionToken(): ?string
    {
        return $this->clientSessionToken;
    }

    /**
     * @return string|null
     */
    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }
}
