<?php

namespace Yoti\DocScan\Session\Retrieve\Instructions\Branch;

class LocationResponse
{
    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @param array<string, float> $locationData
     */
    public function __construct(array $locationData)
    {
        $this->latitude = $locationData['latitude'];
        $this->longitude = $locationData['longitude'];
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }
}
