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
     * @var bool|null
     */
    private $allowNonLatinDocuments;

    /**
     * @var bool|null
     */
    private $allowExpiredDocuments;

    /**
     * @param CountryRestriction|null $countryRestriction
     * @param TypeRestriction|null $typeRestriction
     * @param bool|null $allowNonLatinDocuments
     * @param bool|null $allowExpiredDocuments
     */
    public function __construct(
        ?CountryRestriction $countryRestriction,
        ?TypeRestriction $typeRestriction,
        ?bool $allowNonLatinDocuments,
        ?bool $allowExpiredDocuments
    ) {
        parent::__construct(Constants::ORTHOGONAL_RESTRICTIONS);
        $this->countryRestriction = $countryRestriction;
        $this->typeRestriction = $typeRestriction;
        $this->allowNonLatinDocuments = $allowNonLatinDocuments;
        $this->allowExpiredDocuments = $allowExpiredDocuments;
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

        if (isset($this->allowNonLatinDocuments)) {
            $jsonData->allow_non_latin_documents = $this->allowNonLatinDocuments;
        }

        if (isset($this->allowExpiredDocuments)) {
            $jsonData->allow_expired_documents = $this->allowExpiredDocuments;
        }

        return $jsonData;
    }

    /**
     * @return bool|null
     */
    public function isAllowNonLatinDocuments(): ?bool
    {
        return $this->allowNonLatinDocuments;
    }

    /**
     * @return bool|null
     */
    public function isAllowExpiredDocuments(): ?bool
    {
        return $this->allowExpiredDocuments;
    }
}
