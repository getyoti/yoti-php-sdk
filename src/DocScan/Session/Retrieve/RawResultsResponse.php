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
     * @param array <string, mixed> $rawResults
     * @throws \Yoti\Exception\DateTimeException
     */
    public function __construct(array $rawResults)
    {
        $this->media = new MediaResponse($rawResults['media']);
    }

    /**
     * @return MediaResponse
     */
    public function getMedia(): MediaResponse
    {
        return $this->media;
    }
}
