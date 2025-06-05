<?php

namespace Yoti\Identity;

use Yoti\Identity\Content\ApplicationContent;
use Yoti\Identity\Content\UserContent;
use Yoti\Profile\ApplicationProfile;
use Yoti\Profile\ExtraData;
use Yoti\Profile\UserProfile;

class ReceiptBuilder
{
    private string $id;

    private string $sessionId;

    private \DateTime $timestamp;

    private ?ApplicationContent $applicationContent = null;

    private ?UserContent $userContent = null;

    private ?string $rememberMeId = null;

    private ?string $parentRememberMeId = null;

    private ?string $error = null;
    private ?ErrorReason $errorReason = null;


    public function withId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function withSessionId(string $sessionId): self
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    public function withRememberMeId(?string $rememberMeId = null): self
    {
        $this->rememberMeId = $rememberMeId;

        return $this;
    }

    public function withParentRememberMeId(?string $parentRememberMeId = null): self
    {
        $this->parentRememberMeId = $parentRememberMeId;

        return $this;
    }

    public function withTimestamp(\DateTime $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function withApplicationContent(ApplicationProfile $profile, ?ExtraData $extraData = null): self
    {
        $this->applicationContent = new ApplicationContent($profile, $extraData);

        return $this;
    }

    public function withUserContent(?UserProfile $profile = null, ?ExtraData $extraData = null): self
    {
        $this->userContent = new UserContent($profile, $extraData);

        return $this;
    }

    public function withError(?string $error = null): self
    {
        $this->error = $error;

        return $this;
    }

    public function withErrorReason(?ErrorReason $errorReason = null): self
    {
        $this->errorReason = $errorReason;

        return $this;
    }

    public function build(): Receipt
    {
        return new Receipt(
            $this->id,
            $this->sessionId,
            $this->timestamp,
            $this->applicationContent,
            $this->userContent,
            $this->rememberMeId,
            $this->parentRememberMeId,
            $this->error,
            $this->errorReason
        );
    }
}
