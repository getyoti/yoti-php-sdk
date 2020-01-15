<?php

namespace Yoti\Aml;

class Address implements \JsonSerializable
{
    const POSTCODE_ATTR = 'post_code';
    const COUNTRY_ATTR = 'country';

    /**
     * @var string
     */
    private $postcode;

    /**
     * @var \Yoti\Aml\Country
     */
    private $country;

    /**
     * AmlAddress constructor.
     *
     * @param \Yoti\Aml\Country $country
     * @param null|string $postcode
     */
    public function __construct(Country $country, string $postcode = null)
    {
        $this->country = $country;
        $this->postcode = $postcode;
    }

    /**
     * @return Yoti\Aml\Country
     */
    public function getCountry(): Country
    {
        return $this->country;
    }

    /**
     * @return null|string
     */
    public function getPostcode(): string
    {
        return $this->postcode;
    }

    /**
     * Get address data.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            self::POSTCODE_ATTR => $this->postcode,
            self::COUNTRY_ATTR => $this->country->getCode(),
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this);
    }
}
