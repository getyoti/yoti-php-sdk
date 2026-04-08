<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

class StructuredPostalAddressBuilder
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
     * @param int $addressFormat
     * @return $this
     */
    public function withAddressFormat(int $addressFormat): self
    {
        $this->addressFormat = $addressFormat;
        return $this;
    }

    /**
     * @param string $buildingNumber
     * @return $this
     */
    public function withBuildingNumber(string $buildingNumber): self
    {
        $this->buildingNumber = $buildingNumber;
        return $this;
    }

    /**
     * @param string $addressLine1
     * @return $this
     */
    public function withAddressLine1(string $addressLine1): self
    {
        $this->addressLine1 = $addressLine1;
        return $this;
    }

    /**
     * @param string $townCity
     * @return $this
     */
    public function withTownCity(string $townCity): self
    {
        $this->townCity = $townCity;
        return $this;
    }

    /**
     * @param string $postalCode
     * @return $this
     */
    public function withPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @param string $countryIso
     * @return $this
     */
    public function withCountryIso(string $countryIso): self
    {
        $this->countryIso = $countryIso;
        return $this;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function withCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @param string $formattedAddress
     * @return $this
     */
    public function withFormattedAddress(string $formattedAddress): self
    {
        $this->formattedAddress = $formattedAddress;
        return $this;
    }

    /**
     * @return StructuredPostalAddress
     */
    public function build(): StructuredPostalAddress
    {
        return new StructuredPostalAddress(
            $this->addressFormat,
            $this->buildingNumber,
            $this->addressLine1,
            $this->townCity,
            $this->postalCode,
            $this->countryIso,
            $this->country,
            $this->formattedAddress
        );
    }
}
