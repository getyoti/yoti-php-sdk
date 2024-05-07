<?php

namespace Yoti\Identity;

class ReceiptItemKey
{
    private string $id;

    private string $iv;

    private string $value;

    /**
     * @param array<string, mixed> $sessionData
     */
    public function __construct(array $sessionData)
    {
        $this->id = $sessionData['id'];
        $this->iv = $sessionData['iv'];
        $this->value = $sessionData['value'];
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
    public function getIv(): string
    {
        return $this->iv;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
