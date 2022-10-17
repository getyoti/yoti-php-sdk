<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters\Orthogonal;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Create\Filters\DocumentFilter;

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
     * @var bool|null
     */
    private $allowNonLatinDocuments;

    /**
     * @var bool|null
     */
    private $allowExpiredDocuments;

    /**
     * @param string[] $countryCodes
     *
     * @return $this
     */
    public function withWhitelistedCountries(array $countryCodes): self
    {
        $this->countryRestriction = new CountryRestriction(
            Constants::INCLUSION_WHITELIST,
            $countryCodes
        );
        return $this;
    }

    /**
     * @param string[] $countryCodes
     *
     * @return $this
     */
    public function withBlacklistedCountries(array $countryCodes): self
    {
        $this->countryRestriction = new CountryRestriction(
            Constants::INCLUSION_BLACKLIST,
            $countryCodes
        );
        return $this;
    }


    /**
     * @param string[] $documentTypes
     *
     * @return $this
     */
    public function withWhitelistedDocumentTypes(array $documentTypes): self
    {
        $this->typeRestriction = new TypeRestriction(
            Constants::INCLUSION_WHITELIST,
            $documentTypes
        );
        return $this;
    }

    /**
     * @param string[] $documentTypes
     *
     * @return $this
     */
    public function withBlacklistedDocumentTypes(array $documentTypes): self
    {
        $this->typeRestriction = new TypeRestriction(
            Constants::INCLUSION_BLACKLIST,
            $documentTypes
        );
        return $this;
    }

    /**
     * @return $this
     */
    public function withAllowNonLatinDocuments(): OrthogonalRestrictionsFilterBuilder
    {
        $this->allowNonLatinDocuments = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function withAllowExpiredDocuments(): OrthogonalRestrictionsFilterBuilder
    {
        $this->allowExpiredDocuments = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function withDenyExpiredDocuments(): OrthogonalRestrictionsFilterBuilder
    {
        $this->allowExpiredDocuments = false;
        return $this;
    }

    /**
     * @return OrthogonalRestrictionsFilter
     */
    public function build(): DocumentFilter
    {
        return new OrthogonalRestrictionsFilter(
            $this->countryRestriction,
            $this->typeRestriction,
            $this->allowNonLatinDocuments,
            $this->allowExpiredDocuments
        );
    }
}
