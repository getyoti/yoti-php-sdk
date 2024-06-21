<?php

namespace Yoti\Identity;

final class ShareSessionNotification implements \JsonSerializable
{
    private string $url;

    private string $method;

    private bool $verifyTls;

    /**
     * @var array<string,string>
     */
    private array $headers;

    /**
     * @param string[] $headers
     */
    public function __construct(string $url, string $method, bool $verifyTls, array $headers)
    {
        $this->url = $url;
        $this->method = $method;
        $this->verifyTls = $verifyTls;
        $this->headers = $headers;
    }

    public function jsonSerialize(): object
    {
        return (object)[
            'url' => $this->getUrl(),
            'method' => $this->getMethod(),
            'verifyTls' => $this->isVerifyTls(),
            'headers' => $this->getHeaders(),
        ];
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return bool
     */
    public function isVerifyTls(): bool
    {
        return $this->verifyTls;
    }

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
