<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\Util\Json;

class RequestedDocumentAuthenticityCheckConfig implements RequestedCheckConfigInterface
{
    /**
     * @var string|null
     */
    private $manualCheck;

    public function __construct(?string $manualCheck)
    {
        $this->manualCheck = $manualCheck;
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        return (object) Json::withoutNullValues([
            'manual_check' => $this->getManualCheck(),
        ]);
    }

    /**
     * @return string|null
     */
    public function getManualCheck(): ?string
    {
        return $this->manualCheck;
    }
}
