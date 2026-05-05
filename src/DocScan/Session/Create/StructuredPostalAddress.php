<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

use JsonSerializable;
use stdClass;
use Yoti\Util\Json;

class StructuredPostalAddress implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $addressFormat;

    /**
     * @var string|null
     */
    private $buildingNumber;

    /**
     * @var string|null
     */
    private $addressLine1;

    /**
     * @var string|null
     */
    private $townCity;

    /**
     * @var string|null
     */
    private $postalCode;

    /**
     * @var string|null
     */
    private $countryIso;

    /**
     * @var string|null
     */
    private $country;

    /**
     * @var string|null
     */
    private $formattedAddress;

    /**
     * @param int|null $addressFormat
     * @param string|null $buildingNumber
     * @param string|null $addressLine1
     * @param string|null $townCity
     * @param string|null $postalCode
     * @param string|null $countryIso
     * @param string|null $country
     * @param string|null $formattedAddress
     */
    public function __construct(
        ?int $addressFormat,
        ?string $buildingNumber,
        ?string $addressLine1,
        ?string $townCity,
        ?string $postalCode,
        ?string $countryIso,
        ?string $country,
        ?string $formattedAddress
    ) {
        $this->addressFormat = $addressFormat;
        $this->buildingNumber = $buildingNumber;
        $this->addressLine1 = $addressLine1;
        $this->townCity = $townCity;
        $this->postalCode = $postalCode;
        $this->countryIso = $countryIso;
        $this->country = $country;
        $this->formattedAddress = $formattedAddress;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        return (object) Json::withoutNullValues([
            'address_format' => $this->addressFormat,
            'building_number' => $this->buildingNumber,
            'address_line1' => $this->addressLine1,
            'town_city' => $this->townCity,
            'postal_code' => $this->postalCode,
            'country_iso' => $this->countryIso,
            'country' => $this->country,
            'formatted_address' => $this->formattedAddress,
        ]);
    }

    /**
     * @return int|null
     */
    public function getAddressFormat(): ?int
    {
        return $this->addressFormat;
    }

    /**
     * @return string|null
     */
    public function getBuildingNumber(): ?string
    {
        return $this->buildingNumber;
    }

    /**
     * @return string|null
     */
    public function getAddressLine1(): ?string
    {
        return $this->addressLine1;
    }

    /**
     * @return string|null
     */
    public function getTownCity(): ?string
    {
        return $this->townCity;
    }

    /**
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * @return string|null
     */
    public function getCountryIso(): ?string
    {
        return $this->countryIso;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @return string|null
     */
    public function getFormattedAddress(): ?string
    {
        return $this->formattedAddress;
    }
}
