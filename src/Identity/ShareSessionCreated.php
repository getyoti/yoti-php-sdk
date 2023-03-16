<?php

namespace Yoti\Identity;

use Yoti\Util\Validation;

/**
 * Defines the Share session.
 */
class ShareSessionCreated implements \JsonSerializable
{
    private string $id;

    private string $status;

    private string $expiry;

    /**
     * @param string[] $sessionData
     */
    public function __construct(array $sessionData)
    {
        if (isset($sessionData['id'])) {
            Validation::isString($sessionData['id'], 'id');
            $this->id = $sessionData['id'];
        }

        if (isset($sessionData['status'])) {
            Validation::isString($sessionData['status'], 'status');
            $this->status = $sessionData['status'];
        }

        if (isset($sessionData['expiry'])) {
            Validation::isString($sessionData['expiry'], 'expiry');
            $this->expiry = $sessionData['expiry'];
        }
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
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getExpiry(): string
    {
        return $this->expiry;
    }

    public function jsonSerialize(): object
    {
        return (object)[
            'id' => $this->getId(),
            'status' => $this->getStatus(),
            'expiry' => $this->getExpiry(),
        ];
    }
}
