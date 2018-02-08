<?php
namespace Yoti\Entity;

class AmlAddress
{
    const POSTCODE_ATTR = 'post_code';
    const COUNTRY_ATTR = 'country';

    /**
     * @var string
     */
    private $postcode;

    /**
     * @var \Yoti\Entity\Country
     */
    private $country;

    /**
     * AmlAddress constructor.
     *
     * @param \Yoti\Entity\Country $country
     * @param null|string $postcode
     */
    public function __construct(Country $country, $postcode = NULL)
    {
        $this->country = $country;
        $this->postcode = $postcode;
    }

    /**
     * @param Country $country
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param $postcode
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }

    /**
     * @return null|string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Get address data.
     *
     * @return array
     */
    public function getData()
    {
        return [
            self::POSTCODE_ATTR => $this->postcode,
            self::COUNTRY_ATTR => $this->country->getCode(),
        ];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->getData());
    }
}