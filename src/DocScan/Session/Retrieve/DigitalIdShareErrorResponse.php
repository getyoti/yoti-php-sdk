<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class DigitalIdShareErrorResponse
{
    /**
     * @var string|null
     */
    private $code;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @param array<string, mixed> $error
     */
    public function __construct(array $error)
    {
        $this->code = $error['code'] ?? null;
        $this->description = $error['description'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
}
