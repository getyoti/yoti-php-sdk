<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters\Orthogonal;

use Yoti\DocScan\Constants;

class CountryRestrictionBuilder
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
     * @return self
     */
    public function forWhitelist(): self
    {
        $this->inclusion = Constants::INCLUSION_WHITELIST;
        return $this;
    }

    /**
     * @return self
     */
    public function forBlacklist(): self
    {
        $this->inclusion = Constants::INCLUSION_BLACKLIST;
        return $this;
    }

    /**
     * @return self
     */
    public function withCountryCode(string $countryCode): self
    {
        $this->countryCodes[] = $countryCode;
        return $this;
    }

    /**
     * @return CountryRestriction
     */
    public function build(): CountryRestriction
    {
        return new CountryRestriction($this->inclusion, $this->countryCodes);
    }
}
