<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use stdClass;
use Yoti\Util\Json;

class RequestedDocumentAuthenticityCheckConfig implements RequestedCheckConfigInterface
{
    /**
     * @var string|null
     */
    private $manualCheck;

    /**
     * @var IssuingAuthoritySubCheck|null
     */
    private $issuingAuthoritySubCheck;

    /**
     * @param string|null $manualCheck
     * @param IssuingAuthoritySubCheck|null $issuingAuthoritySubCheck
     */
    public function __construct(?string $manualCheck, ?IssuingAuthoritySubCheck $issuingAuthoritySubCheck = null)
    {
        $this->manualCheck = $manualCheck;
        $this->issuingAuthoritySubCheck = $issuingAuthoritySubCheck;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        return (object) Json::withoutNullValues([
            'manual_check' => $this->getManualCheck(),
            'issuing_authority_sub_check' => $this->getIssuingAuthoritySubCheck(),
        ]);
    }

    /**
     * @return string|null
     */
    public function getManualCheck(): ?string
    {
        return $this->manualCheck;
    }

    /**
     * @return IssuingAuthoritySubCheck|null
     */
    public function getIssuingAuthoritySubCheck(): ?IssuingAuthoritySubCheck
    {
        return $this->issuingAuthoritySubCheck;
    }
}
