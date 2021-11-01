<?php

namespace Yoti\DocScan\Session\Retrieve;

class CreateFaceCaptureResourceResponse
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var int
     */
    private $frames;

    /**
     * @param array<string,mixed> $sessionData
     */
    public function __construct(array $sessionData)
    {
        $this->id = $sessionData['id'] ?? null;
        $this->frames = $sessionData['frames'] ?? null;
    }

    /**
     * Returns the ID of the newly created Face Capture resource
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Returns the number of image frames required
     *
     * @return int
     */
    public function getFrames(): int
    {
        return $this->frames;
    }
}
