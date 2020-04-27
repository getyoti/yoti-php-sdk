<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters\Document;

use Yoti\Util\Validation;

class DocumentRestriction implements \JsonSerializable
{
    /**
     * @var string[]|null
     */
    private $documentTypes;

    /**
     * @var string[]|null
     */
    private $countryCodes;

    /**
     * @param string[]|null $countryCodes
     * @param string[]|null $documentTypes
     */
    public function __construct(?array $countryCodes, ?array $documentTypes)
    {
        if (isset($countryCodes)) {
            Validation::isArrayOfStrings($countryCodes, 'countryCodes');
            $this->countryCodes = $countryCodes;
        }

        if (isset($documentTypes)) {
            Validation::isArrayOfStrings($documentTypes, 'documentTypes');
            $this->documentTypes = $documentTypes;
        }
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        $jsonData = new \stdClass();

        if (isset($this->documentTypes)) {
            $jsonData->document_types = $this->documentTypes;
        }

        if (isset($this->countryCodes)) {
            $jsonData->country_codes = $this->countryCodes;
        }

        return $jsonData;
    }
}
