<?php
namespace Yoti\Entity;

class AmlAddress
{
    const POSTCODE_ATTR = 'post_code';
    const COUNTRY_ATTR = 'country';

    private $postcode;

    private $country;

    public function __construct(Country $country, $postcode)
    {
        $this->country = $country;
        $this->postcode = $postcode;
    }

    public function setCountry(Country $country)
    {
        $this->country = $country;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function getData()
    {
        return [
            self::POSTCODE_ATTR => $this->postcode,
            self::COUNTRY_ATTR => $this->country,
        ];
    }
}