<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters\Orthogonal;

use Yoti\DocScan\Session\Create\Filters\RequiredDocumentFilter;

class OrthogonalRestrictionsFilterBuilder
{
    /**
     * @var CountryRestriction
     */
    private $countryRestriction;

    /**
     * @var TypeRestriction
     */
    private $typeRestriction;

    /**
     * @param CountryRestriction $countryRestriction
     *
     * @return $this
     */
    public function withCountryRestriction(CountryRestriction $countryRestriction): self
    {
        $this->countryRestriction = $countryRestriction;
        return $this;
    }

    /**
     * @param TypeRestriction $typeRestriction
     *
     * @return $this
     */
    public function withTypeRestriction(TypeRestriction $typeRestriction): self
    {
        $this->typeRestriction = $typeRestriction;
        return $this;
    }

    /**
     * @return OrthogonalRestrictionsFilter
     */
    public function build(): RequiredDocumentFilter
    {
        return new OrthogonalRestrictionsFilter($this->countryRestriction, $this->typeRestriction);
    }
}
