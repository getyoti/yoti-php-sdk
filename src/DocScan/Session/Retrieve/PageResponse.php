<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class PageResponse
{

    /**
     * @var string|null
     */
    private $captureMethod;

    /**
     * @var MediaResponse|null
     */
    private $media;

    /**
     * PageInfo constructor.
     * @param array<string, mixed> $page
     */
    public function __construct(array $page)
    {
        $this->captureMethod = $page['capture_method'] ?? null;

        $this->media = isset($page['media'])
            ? new MediaResponse($page['media'])
            : null;
    }

    /**
     * @return string|null
     */
    public function getCaptureMethod(): ?string
    {
        return $this->captureMethod;
    }

    /**
     * @return MediaResponse|null
     */
    public function getMedia(): ?MediaResponse
    {
        return $this->media;
    }
}
