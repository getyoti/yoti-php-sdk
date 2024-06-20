<?php

namespace Yoti\Identity;

use Yoti\Identity\Extension\Extension;
use Yoti\Identity\Policy\Policy;

class ShareSessionRequestBuilder
{
    /**
     * @var array<string, object>
     */
    private ?array $subject = null;

    private Policy $policy;

    /**
     * @var Extension[]
     */
    private ?array $extensions = null;

    private string $redirectUri;

    private ?ShareSessionNotification $notification = null;

    /**
     * @param array<string, object> $subject
     */
    public function withSubject(array $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function withPolicy(Policy $policy): self
    {
        $this->policy = $policy;

        return $this;
    }

    /**
     * @param Extension[] $extensions
     */
    public function withExtensions(array $extensions): self
    {
        $this->extensions = $extensions;

        return $this;
    }

    public function withExtension(Extension $extension): self
    {
        $this->extensions[] = $extension;

        return $this;
    }

    public function withRedirectUri(string $redirectUri): self
    {
        $this->redirectUri = $redirectUri;

        return $this;
    }

    public function withNotification(ShareSessionNotification $notification): ShareSessionRequestBuilder
    {
        $this->notification = $notification;

        return $this;
    }

    public function build(): ShareSessionRequest
    {
        return new ShareSessionRequest(
            $this->policy,
            $this->redirectUri,
            $this->extensions,
            $this->subject,
            $this->notification
        );
    }
}
