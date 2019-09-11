<?php

namespace Yoti\ShareUrl\Extension;

const LOCATION_CONSTRAINT = 'LOCATION_CONSTRAINT';

/**
 * Builds location constraint Extension.
 */
class LocationConstraintExtensionBuilder
{
    /**
     * Location constraint extension type.
     */
    const LOCATION_CONSTRAINT = 'LOCATION_CONSTRAINT';

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var float
     */
    private $radius = 150;

    /**
     * @var float
     */
    private $maxUncertainty = 150;

    /**
     * Allows you to specify the Latitude of the user's expected location
     *
     * @param float $latitude
     *
     * @return LocationConstraintExtensionBuilder
     */
    public function withLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * Allows you to specify the Longitude of the user's expected location
     *
     * @param float $longitude
     *
     * @return LocationConstraintExtensionBuilder
     */
    public function withLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * Radius of the circle, centred on the specified location coordinates, where the device is
     * allowed to perform the share.
     *
     * If not provided, a default value of 150m will be used.
     *
     * @param float $radius
     *   The allowable distance, in metres, from the given lat/long location
     *
     * @return LocationConstraintExtensionBuilder
     */
    public function withRadius($radius)
    {
        $this->radius = $radius;
        return $this;
    }

    /**
     * Maximum acceptable distance, in metres, of the area of uncertainty associated with the device
     * location coordinates.
     *
     * If not provided, a default value of 150m will be used.
     *
     * @param float $maxUncertainty
     *   Maximum allowed measurement uncertainty, in metres
     *
     * @return LocationConstraintExtensionBuilder
     */
    public function withMaxUncertainty($maxUncertainty)
    {
        $this->maxUncertainty = $maxUncertainty;
        return $this;
    }

    /**
     * @return Extension
     *   Extension with LocationConstraintExtensionContent content
     */
    public function build()
    {
        $content = new LocationConstraintContent(
            $this->latitude,
            $this->longitude,
            $this->radius,
            $this->maxUncertainty
        );
        return new Extension(LOCATION_CONSTRAINT, $content);
    }
}
