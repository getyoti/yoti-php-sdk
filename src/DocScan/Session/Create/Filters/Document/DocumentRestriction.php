<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters\Document;

use Yoti\Util\Validation;

class DocumentRestriction implements \JsonSerializable
{
    /**
     * @var string[]
     */
    private $documentTypes;

    /**
     * @var string[]
     */
    private $countryCodes;

    /**
     * @param string[] $countryCodes
     * @param string[] $documentTypes
     */
    public function __construct(array $countryCodes, array $documentTypes)
    {
        Validation::isArrayOfStrings($countryCodes, 'countryCodes');
        $this->countryCodes = $countryCodes;

        Validation::isArrayOfStrings($documentTypes, 'documentTypes');
        $this->documentTypes = $documentTypes;
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        $jsonData = new \stdClass();

        if (count($this->documentTypes) > 0) {
            $jsonData->document_types = $this->documentTypes;
        }

        if (count($this->countryCodes) > 0) {
            $jsonData->country_codes = $this->countryCodes;
        }

        return $jsonData;
    }
}
