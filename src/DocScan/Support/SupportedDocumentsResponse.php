<?php

declare(strict_types=1);

namespace Yoti\DocScan\Support;

class SupportedDocumentsResponse
{
    /**
     * @var SupportedCountry[]
     */
    private $supportedCountries = [];

    /**
     * @param array<string, mixed> $response
     */
    public function __construct($response)
    {
        if (isset($response['supported_countries'])) {
            $this->supportedCountries = array_map(
                function ($country): SupportedCountry {
                    return new SupportedCountry($country);
                },
                $response['supported_countries']
            );
        }
    }

    /**
     * @return SupportedCountry[]
     */
    public function getSupportedCountries(): array
    {
        return $this->supportedCountries;
    }
}
