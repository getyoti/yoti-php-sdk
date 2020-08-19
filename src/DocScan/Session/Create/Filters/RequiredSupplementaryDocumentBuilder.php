<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters;

use Yoti\DocScan\Session\Create\Objective\Objective;
use Yoti\Util\Validation;

class RequiredSupplementaryDocumentBuilder
{
    /**
     * @var Objective
     */
    private $objective;

    /**
     * @var string[]|null
     */
    private $countryCodes;

    /**
     * @var string[]|null
     */
    private $documentTypes;

    /**
     * @param string[] $countryCodes
     *
     * @return $this
     */
    public function withCountryCodes(array $countryCodes): self
    {
        Validation::isArrayOfStrings($countryCodes, 'countryCodes');
        $this->countryCodes = $countryCodes;
        return $this;
    }

    /**
     * @param string[] $documentTypes
     *
     * @return $this
     */
    public function withDocumentTypes(array $documentTypes): self
    {
        Validation::isArrayOfStrings($documentTypes, 'documentTypes');
        $this->documentTypes = $documentTypes;
        return $this;
    }

    /**
     * @param Objective $objective
     *
     * @return self
     */
    public function withObjective(Objective $objective): self
    {
        $this->objective = $objective;
        return $this;
    }

    /**
     * @return RequiredSupplementaryDocument
     */
    public function build(): RequiredSupplementaryDocument
    {
        Validation::notNull($this->objective, 'objective');

        return new RequiredSupplementaryDocument(
            $this->objective,
            $this->documentTypes,
            $this->countryCodes
        );
    }
}
