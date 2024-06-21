<?php

namespace Yoti\Identity;

use Yoti\Identity\Content\ApplicationContent;
use Yoti\Identity\Content\UserContent;
use Yoti\Profile\ExtraData;
use Yoti\Profile\UserProfile;

class Receipt
{
    private string $id;

    private string $sessionId;

    private \DateTime $timestamp;

    private ApplicationContent $applicationContent;

    private UserContent $userContent;

    private ?string $rememberMeId;

    private ?string $parentRememberMeId;

    private ?string $error;

    public function __construct(
        string $id,
        string $sessionId,
        \DateTime $timestamp,
        ApplicationContent $applicationContent,
        UserContent $userContent,
        ?string $rememberMeId,
        ?string $parentRememberMeId,
        ?string $error
    ) {
        $this->id = $id;
        $this->sessionId = $sessionId;
        $this->timestamp = $timestamp;
        $this->applicationContent = $applicationContent;
        $this->userContent = $userContent;
        $this->rememberMeId = $rememberMeId;
        $this->parentRememberMeId = $parentRememberMeId;
        $this->error = $error;
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

    public function getProfile(): ?UserProfile
    {
        return $this->userContent->getProfile();
    }

    public function getExtraData(): ?ExtraData
    {
        return $this->userContent->getExtraData();
    }

    public function getApplicationContent(): ApplicationContent
    {
        return $this->applicationContent;
    }

    public function getUserContent(): UserContent
    {
        return $this->userContent;
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
}
