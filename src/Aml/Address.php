<?php

declare(strict_types=1);

namespace Yoti\Aml;

use Yoti\Util\Json;

class Address implements \JsonSerializable
{
    private const POSTCODE_ATTR = 'post_code';
    private const COUNTRY_ATTR = 'country';

    /**
     * @var ?string
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
    public function __construct(Country $country, ?string $postcode = null)
    {
        $this->country = $country;
        $this->postcode = $postcode;
    }

    /**
     * @return \Yoti\Aml\Country
     */
    public function getCountry(): Country
    {
        return $this->country;
    }

    /**
     * @return null|string
     */
    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    /**
     * Get address data.
     *
     * @return array<string, string|Country|null>
     */
    public function jsonSerialize(): array
    {
        return [
            self::POSTCODE_ATTR => $this->getPostcode(),
            self::COUNTRY_ATTR => $this->getCountry(),
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
