<?php

namespace Yoti\DocScan\Session\Retrieve;

class SupplementaryDocTextExtractionTaskResponse extends TaskResponse
{
    /**
     * @return GeneratedSupplementaryDocTextDataCheckResponse[]
     */
    public function getGeneratedTextDataChecks(): array
    {
        return $this->filterGeneratedChecksByType(GeneratedSupplementaryDocTextDataCheckResponse::class);
    }
}
