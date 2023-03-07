<?php

namespace Yoti\Identity;

class ShareSessionQrCode implements \JsonSerializable
{
    private string $id;

    private string $uri;

    /**
     * @param string[] $sessionData
     */
    public function __construct(array $sessionData)
    {
        if (isset($sessionData['id'])) {
            $this->id = $sessionData['id'];
        }

        if (isset($sessionData['uri'])) {
            $this->uri = $sessionData['uri'];
        }
    }

    public function jsonSerialize(): object
    {
        return (object)[
            'id' => $this->id,
            'uri' => $this->uri
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
    public function getUri(): string
    {
        return $this->uri;
    }
}
