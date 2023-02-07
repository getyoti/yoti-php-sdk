<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Retrieve;

class FileResponse
{
    /**
     * @var MediaResponse|null
     */
    private $media;

    /**
     * @param array<string, mixed> $file
     */
    public function __construct(array $file)
    {
        $this->media = isset($file['media'])
            ? new MediaResponse($file['media'])
            : null;
    }

    /**
     * @return MediaResponse|null
     */
    public function getMedia(): ?MediaResponse
    {
        return $this->media;
    }
}
