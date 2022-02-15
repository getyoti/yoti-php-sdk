<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration;

use Yoti\DocScan\Session\Retrieve\Configuration\Capture\CaptureResponse;

class SessionConfigurationResponse
{
    /**
     * @var int|null
     */
    private $clientSessionTokenTtl;

    /**
     * @var string|null
     */
    private $sessionId;

    /**
     * @var array<int,string>|null
     */
    private $requestedChecks;

    /**
     * @var CaptureResponse|null
     */
    private $capture;

    /**
     * @param array<string, mixed> $sessionData
     */
    public function __construct(array $sessionData)
    {
        $this->clientSessionTokenTtl = $sessionData['client_session_token_ttl'] ?? null;
        $this->sessionId = $sessionData['session_id'] ?? null;
        $this->requestedChecks = $sessionData['requested_checks'] ?? null;
        $this->capture = isset($sessionData['capture']) ? new CaptureResponse($sessionData['capture']) : null;
    }

    /**
     * Returns the amount of time remaining in seconds until the session
     * expires.
     *
     * @return int|null
     */
    public function getClientSessionTokenTtl(): ?int
    {
        return $this->clientSessionTokenTtl;
    }

    /**
     * The session ID that the configuration belongs to
     *
     * @return string|null
     */
    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    /**
     * Returns a list of strings, signifying the checks that have been requested
     * in the session
     *
     * @return string[]|null
     */
    public function getRequestedChecks(): ?array
    {
        return $this->requestedChecks;
    }

    /**
     * Returns information about what needs to be captured to fulfill the
     * sessions requirements
     *
     * @return CaptureResponse|null
     */
    public function getCapture(): ?CaptureResponse
    {
        return $this->capture;
    }
}
