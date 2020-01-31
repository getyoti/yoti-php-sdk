<?php

declare(strict_types=1);

namespace Yoti\ShareUrl\Extension;

/**
 * Builds location constraint Extension.
 */
class LocationConstraintExtensionBuilder
{
    /**
     * Location constraint extension type.
     */
    private const LOCATION_CONSTRAINT = 'LOCATION_CONSTRAINT';

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
     * @return $this
     */
    public function withLatitude(float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * Allows you to specify the Longitude of the user's expected location
     *
     * @param float $longitude
     *
     * @return $this
     */
    public function withLongitude(float $longitude): self
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
     * @return $this
     */
    public function withRadius(float $radius): self
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
     * @return $this
     */
    public function withMaxUncertainty(float $maxUncertainty): self
    {
        $this->maxUncertainty = $maxUncertainty;
        return $this;
    }

    /**
     * @return \Yoti\ShareUrl\Extension\Extension
     *   Extension with LocationConstraintExtensionContent content
     */
    public function build(): Extension
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
