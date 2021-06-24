<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class RawResultsResponse
{
    /**
     * @var MediaResponse
     */
    private $media;

    /**
     * @return MediaResponse
     */
    public function getMedia(): MediaResponse
    {
        return $this->media;
    }
}
