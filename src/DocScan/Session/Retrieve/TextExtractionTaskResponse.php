<?php

namespace Yoti\DocScan\Session\Retrieve;

class TextExtractionTaskResponse extends TaskResponse
{
    /**
     * @return GeneratedTextDataCheckResponse[]
     */
    public function getGeneratedTextDataChecks(): array
    {
        return $this->filterGeneratedChecksByType(GeneratedTextDataCheckResponse::class);
    }
}
