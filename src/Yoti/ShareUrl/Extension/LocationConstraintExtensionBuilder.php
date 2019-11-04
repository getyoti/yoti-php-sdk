<?php

namespace Yoti\ShareUrl\Extension;

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
     * @var int|float
     */
    private $latitude;

    /**
     * @var int|float
     */
    private $longitude;

    /**
     * @var int|float
     */
    private $radius = 150;

    /**
     * @var int|float
     */
    private $maxUncertainty = 150;

    /**
     * Allows you to specify the Latitude of the user's expected location
     *
     * @param int|float $latitude
     *
     * @return \Yoti\ShareUrl\Extension\LocationConstraintExtensionBuilder
     */
    public function withLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * Allows you to specify the Longitude of the user's expected location
     *
     * @param int|float $longitude
     *
     * @return \Yoti\ShareUrl\Extension\LocationConstraintExtensionBuilder
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
     * @param int|float $radius
     *   The allowable distance, in metres, from the given lat/long location
     *
     * @return \Yoti\ShareUrl\Extension\LocationConstraintExtensionBuilder
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
     * @param int|float $maxUncertainty
     *   Maximum allowed measurement uncertainty, in metres
     *
     * @return \Yoti\ShareUrl\Extension\LocationConstraintExtensionBuilder
     */
    public function withMaxUncertainty($maxUncertainty)
    {
        $this->maxUncertainty = $maxUncertainty;
        return $this;
    }

    /**
     * @return \Yoti\ShareUrl\Extension\Extension
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

        return new Extension(self::LOCATION_CONSTRAINT, $content);
    }
}
