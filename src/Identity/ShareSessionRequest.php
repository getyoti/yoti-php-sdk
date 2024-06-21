<?php

namespace Yoti\Identity;

use Yoti\Identity\Extension\Extension;
use Yoti\Identity\Policy\Policy;
use Yoti\Util\Validation;

class ShareSessionRequest implements \JsonSerializable
{
    /**
     * @var array<string, object>|null
     */
    private ?array $subject;

    private Policy $policy;

    /**
     * @var Extension[]|null
     */
    private ?array $extensions = null;

    private string $redirectUri;

    private ?ShareSessionNotification $notification;

    /**
     * @param array<string, object>|null $subject
     * @param Policy $policy
     * @param Extension[]|null $extensions
     * @param string $redirectUri
     * @param ShareSessionNotification|null $notification
     */
    public function __construct(
        Policy $policy,
        string $redirectUri,
        ?array $extensions = null,
        ?array $subject = null,
        ?ShareSessionNotification $notification = null
    ) {
        $this->policy = $policy;
        $this->redirectUri = $redirectUri;

        if (null !== $extensions) {
            Validation::isArrayOfType($extensions, [Extension::class], 'extensions');
            $this->extensions = $extensions;
        }

        $this->subject = $subject;
        $this->notification = $notification;
    }

    /**
     * @return array<string, object>|null
     */
    public function getSubject(): ?array
    {
        return $this->subject;
    }

    /**
     * @return Policy
     */
    public function getPolicy(): Policy
    {
        return $this->policy;
    }

    /**
     * @return Extension[]|null
     */
    public function getExtensions(): ?array
    {
        return $this->extensions;
    }

    /**
     * @return string
     */
    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    /**
     * @return ShareSessionNotification|null
     */
    public function getNotification(): ?ShareSessionNotification
    {
        return $this->notification;
    }

    public function jsonSerialize(): \stdClass
    {
        $data = new \stdClass();
        $data->policy = $this->getPolicy();
        $data->redirectUri = $this->getRedirectUri();
        if (null !== $this->getSubject()) {
            $data->subject = $this->getSubject();
        }
        if (null !== $this->getExtensions()) {
            $data->extensions = $this->getExtensions();
        }
        if (null !== $this->getNotification()) {
            $data->notification = $this->getNotification();
        }

        return $data;
    }
}
