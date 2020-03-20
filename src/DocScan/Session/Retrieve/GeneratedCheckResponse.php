<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class GeneratedCheckResponse
{

    /**
     * @var string|null
     */
    private $id;

    /**
     * GeneratedCheckResponse constructor.
     * @param array<string, mixed> $generatedCheck
     */
    public function __construct(array $generatedCheck)
    {
        $this->id = $generatedCheck['id'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }
}
