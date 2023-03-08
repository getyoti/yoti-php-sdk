<?php

namespace Yoti\Identity;

use Yoti\Identity\Extension\Extension;

class ShareSessionFetchedQrCode implements \JsonSerializable
{
    private string $id;

    private string $expiry;

    private string $policy;

    /**
     * @var Extension[]
     */
    private array $extensions;

    private ShareSession $session;

    private string $redirectUri;

    /**
     * @param array<string, mixed> $sessionData
     */
    public function __construct(array $sessionData)
    {
        if (isset($sessionData['id'])) {
            $this->id = $sessionData['id'];
        }
        if (isset($sessionData['expiry'])) {
            $this->expiry = $sessionData['expiry'];
        }
        if (isset($sessionData['policy'])) {
            $this->policy = $sessionData['policy'];
        }
        if (isset($sessionData['extensions'])) {
            foreach ($sessionData['extensions'] as $extension) {
                $this->extensions[] = new Extension($extension['type'], $extension['content']);
            }
        }
        if (isset($sessionData['session'])) {
            $this->session = new ShareSession($sessionData['session']);
        }
        if (isset($sessionData['redirectUri'])) {
            $this->redirectUri = $sessionData['redirectUri'];
        }
    }

    public function jsonSerialize(): object
    {
        return (object)[
            'id' => $this->id,
            'expiry' => $this->expiry,
            'policy' => $this->policy,
            'extensions' => $this->extensions,
            'session' => $this->session,
            'redirectUri' => $this->redirectUri,
        ];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getExpiry(): string
    {
        return $this->expiry;
    }

    /**
     * @return string
     */
    public function getPolicy(): string
    {
        return $this->policy;
    }

    /**
     * @return Extension[]
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * @return ShareSession
     */
    public function getSession(): ShareSession
    {
        return $this->session;
    }

    /**
     * @return string
     */
    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }
}
