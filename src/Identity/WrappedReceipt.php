<?php

namespace Yoti\Identity;

use Yoti\Exception\DigitalIdentityException;
use Yoti\Exception\EncryptedDataException;
use Yoti\Identity\Content\Content;
use Yoti\Util\DateTime;

class WrappedReceipt
{
    private string $id;

    private string $sessionId;

    private \DateTime $timestamp;

    private Content $content;

    private Content $otherPartyContent;

    private ?string $wrappedItemKeyId = null;

    private ?string $wrappedKey;

    private ?string $rememberMeId = null;

    private ?string $parentRememberMeId = null;

    private ?string $error = null;
    private ?ErrorReason $errorReason = null;

    /**
     * @param array<string, mixed> $sessionData
     */
    public function __construct(array $sessionData)
    {
        $this->id = $sessionData['id'];
        $this->sessionId = $sessionData['sessionId'];
        $this->timestamp = DateTime::stringToDateTime($sessionData['timestamp']);
        $this->wrappedItemKeyId = $sessionData['wrappedItemKeyId'] ?? null;
        $this->wrappedKey = $sessionData['wrappedKey'] ?? null;

        if (isset($sessionData['content'])) {
            $this->content = new Content(
                $sessionData['content']['profile'] ?? null,
                $sessionData['content']['extraData'] ?? null
            );
        }
        if (isset($sessionData['otherPartyContent'])) {
            $this->otherPartyContent = new Content(
                $sessionData['otherPartyContent']['profile'] ?? null,
                $sessionData['otherPartyContent']['extraData'] ?? null
            );
        }

        if (isset($sessionData['rememberMeId'])) {
            $this->rememberMeId = $this->base64decode($sessionData['rememberMeId']);
        }
        if (isset($sessionData['parentRememberMeId'])) {
            $this->parentRememberMeId = $this->base64decode($sessionData['parentRememberMeId']);
        }
        if (isset($sessionData['error'])) {
            $this->error = $sessionData['error'];
        }
        if (isset($sessionData['errorReason'])) {
            if (isset($sessionData["errorReason"]["requirements_not_met_details"])) {
                $this->errorReason = new ErrorReason(
                    $sessionData["errorReason"]["requirements_not_met_details"]
                );
            }
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * @return string
     * @throws DigitalIdentityException
     */
    public function getProfile(): string
    {
        if (null === $this->content->getProfile()) {
            throw new DigitalIdentityException('Application profile should not be missing');
        }

        return $this->content->getProfile();
    }

    public function getExtraData(): ?string
    {
        return $this->content->getExtraData();
    }

    public function getOtherPartyProfile(): ?string
    {
        return $this->otherPartyContent->getProfile();
    }

    public function getOtherPartyExtraData(): ?string
    {
        return $this->otherPartyContent->getExtraData();
    }

    public function getWrappedItemKeyId(): string
    {
        return $this->wrappedItemKeyId;
    }

    public function getWrappedKey(): string
    {
        return $this->wrappedKey;
    }

    public function getRememberMeId(): ?string
    {
        return $this->rememberMeId;
    }

    public function getParentRememberMeId(): ?string
    {
        return $this->parentRememberMeId;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getErrorReason(): ?ErrorReason
    {
        return $this->errorReason;
    }

    private function base64decode(string $encoded): string
    {
        $decoded = base64_decode($encoded, true);
        if ($decoded === false) {
            throw new EncryptedDataException('Could not decode data');
        }
        return $decoded;
    }
}
