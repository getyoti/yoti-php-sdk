<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration;

use Yoti\DocScan\Session\Retrieve\Configuration\Capture\CaptureResponse;

class SessionConfigurationResponse
{
    /**
     * @var int
     */
    private $clientSessionTokenTtl;

    /**
     * @var string
     */
    private $sessionId;

    /**
     * @var array<int,string>
     */
    private $requestedChecks;

    /**
     * @var CaptureResponse
     */
    private $capture;

    /**
     * @param array<string, mixed> $sessionData
     */
    public function __construct(array $sessionData)
    {
        //TODO Get data and save it
        $this->clientSessionTokenTtl = $sessionData['client_session_token_ttl'] ?? null;
    }

    /**
     * Returns the amount of time remaining in seconds until the session
     * expires.
     *
     * @return int
     */
    public function getClientSessionTokenTtl(): int
    {
        return $this->clientSessionTokenTtl;
    }

    /**
     * The session ID that the configuration belongs to
     *
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * Returns a list of strings, signifying the checks that have been requested
     * in the session
     *
     * @return string[]
     */
    public function getRequestedChecks(): array
    {
        return $this->requestedChecks;
    }

    /**
     * Returns information about what needs to be captured to fulfill the
     * sessions requirements
     *
     * @return CaptureResponse
     */
    public function getCapture(): CaptureResponse
    {
        return $this->capture;
    }
}
