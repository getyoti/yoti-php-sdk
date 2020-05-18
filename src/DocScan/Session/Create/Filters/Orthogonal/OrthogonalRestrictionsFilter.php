<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters\Orthogonal;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Create\Filters\DocumentFilter;

class OrthogonalRestrictionsFilter extends DocumentFilter
{
    /**
     * @var CountryRestriction|null
     */
    private $countryRestriction;

    /**
     * @var TypeRestriction|null
     */
    private $typeRestriction;

    /**
     * @param CountryRestriction|null $countryRestriction
     * @param TypeRestriction|null $typeRestriction
     */
    public function __construct(?CountryRestriction $countryRestriction, ?TypeRestriction $typeRestriction)
    {
        parent::__construct(Constants::ORTHOGONAL_RESTRICTIONS);

        $this->countryRestriction = $countryRestriction;
        $this->typeRestriction = $typeRestriction;
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        $jsonData = parent::jsonSerialize();

        if (isset($this->countryRestriction)) {
            $jsonData->country_restriction = $this->countryRestriction;
        }

        if (isset($this->typeRestriction)) {
            $jsonData->type_restriction = $this->typeRestriction;
        }

        return $jsonData;
    }
}
