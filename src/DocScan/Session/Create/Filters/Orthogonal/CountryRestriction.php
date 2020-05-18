<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters\Orthogonal;

use Yoti\Util\Validation;

class CountryRestriction implements \JsonSerializable
{
    /**
     * @var string
     */
    private $inclusion;

    /**
     * @var string[]
     */
    private $countryCodes;

    /**
     * @param string $inclusion
     * @param string[] $countryCodes
     */
    public function __construct(string $inclusion, array $countryCodes)
    {
        $this->inclusion = $inclusion;

        Validation::isArrayOfStrings($countryCodes, 'countryCodes');
        $this->countryCodes = $countryCodes;
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        return (object) [
            'inclusion' => $this->inclusion,
            'country_codes' => $this->countryCodes,
        ];
    }
}
