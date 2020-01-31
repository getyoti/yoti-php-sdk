<?php

declare(strict_types=1);

namespace Yoti\ShareUrl\Extension;

use Yoti\Util\Json;
use Yoti\Util\Validation;

/**
 * Defines an expected device location constraint.
 */
class LocationConstraintContent implements \JsonSerializable
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
     * @var float
     */
    private $radius;

    /**
     * @var float
     */
    private $maxUncertainty;

    /**
     * @param float $latitude
     *   Latitude of the user's expected location
     * @param float $longitude
     *   Longitude of the user's expected location
     * @param float $radius
     *   Radius of the circle, centred on the specified location
     *   coordinates, where the device is allowed to perform the share
     * @param float $maxUncertainty
     *   Maximum acceptable distance, in metres, of the area of
     *   uncertainty associated with the device location coordinates.
     */
    public function __construct(float $latitude, float $longitude, float $radius, float $maxUncertainty)
    {
        Validation::withinRange($latitude, -90, 90, 'latitude');
        $this->latitude = $latitude;

        Validation::withinRange($longitude, -180, 180, 'longitude');
        $this->longitude = $longitude;

        Validation::notLessThan($radius, 0, 'radius');
        $this->radius = $radius;

        Validation::notLessThan($maxUncertainty, 0, 'maxUncertainty');
        $this->maxUncertainty = $maxUncertainty;
    }

    /**
     * @inheritDoc
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'expected_device_location' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'radius' => $this->radius,
                'max_uncertainty_radius' => $this->maxUncertainty,
            ]
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return Json::encode($this);
    }
}
