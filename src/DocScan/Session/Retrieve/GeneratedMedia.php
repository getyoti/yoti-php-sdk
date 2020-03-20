<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class GeneratedMedia
{

    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $type;

    /**
     * GeneratedMedia constructor.
     * @param array<string, mixed> $generatedMedia
     */
    public function __construct(array $generatedMedia)
    {
        $this->id = $generatedMedia['id'] ?? null;
        $this->type = $generatedMedia['type'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }
}
