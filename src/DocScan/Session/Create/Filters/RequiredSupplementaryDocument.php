<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Create\Objective\Objective;

class RequiredSupplementaryDocument extends RequiredDocument
{
    /**
     * @var Objective
     */
    private $objective;

    /**
     * @var string[]|null
     */
    private $documentTypes;

    /**
     * @var string[]|null
     */
    private $countryCodes;

    /**
     * @param Objective $objective
     * @param string[]|null $documentTypes
     * @param string[]|null $countryCodes
     */
    public function __construct(
        Objective $objective,
        ?array $documentTypes = null,
        ?array $countryCodes = null
    ) {
        parent::__construct(Constants::SUPPLEMENTARY_DOCUMENT);

        $this->objective = $objective;
        $this->documentTypes = $documentTypes;
        $this->countryCodes = $countryCodes;
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        $jsonData = parent::jsonSerialize();

        $jsonData->objective = $this->objective;

        if (isset($this->documentTypes)) {
            $jsonData->document_types = $this->documentTypes;
        }

        if (isset($this->countryCodes)) {
            $jsonData->country_codes = $this->countryCodes;
        }

        return $jsonData;
    }
}
