<?php

namespace Yoti\IDV\Session\Retrieve;

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
