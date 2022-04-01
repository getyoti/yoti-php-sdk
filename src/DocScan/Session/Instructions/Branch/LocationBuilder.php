<?php

namespace Yoti\DocScan\Session\Instructions\Branch;

class LocationBuilder
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
     * Sets the latitude of the location, in decimal degrees (e.g. -40.3992)
     *
     * @param float $latitude
     * @return $this
     */
    public function withLatitude(float $latitude): LocationBuilder
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * Sets the longitude of the location, in decimal degrees (e.g. 20.4821)
     *
     * @param float $longitude
     * @return $this
     */
    public function withLongitude(float $longitude): LocationBuilder
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return Location
     */
    public function build(): Location
    {
        return new Location($this->latitude, $this->longitude);
    }
}
