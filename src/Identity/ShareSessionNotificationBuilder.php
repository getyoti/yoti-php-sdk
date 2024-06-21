<?php

namespace Yoti\Identity;

class ShareSessionNotificationBuilder
{
    private string $url;

    private string $method;

    private bool $verifyTls;

    /**
     * @var array<string,string>
     */
    private array $headers;

    public function withUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function withMethod(string $method = 'POST'): self
    {
        $this->method = $method;

        return $this;
    }

    public function withVerifyTls(bool $verifyTls = true): self
    {
        $this->verifyTls = $verifyTls;

        return $this;
    }

    /**
     * @param string[] $headers
     */
    public function withHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    public function withHeader(string $key, string $header): self
    {
        $this->headers[$key] = $header;

        return $this;
    }

    public function build(): ShareSessionNotification
    {
        return new ShareSessionNotification(
            $this->url,
            $this->method,
            $this->verifyTls,
            $this->headers
        );
    }
}
