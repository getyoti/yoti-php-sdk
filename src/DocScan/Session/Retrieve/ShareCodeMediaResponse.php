<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class ShareCodeMediaResponse
{
    /**
     * @var MediaResponse|null
     */
    private $media;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->media = isset($data['media'])
            ? new MediaResponse($data['media'])
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
