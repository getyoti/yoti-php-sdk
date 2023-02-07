<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Create\Check;

use stdClass;

class RequestedFaceMatchCheckConfig implements RequestedCheckConfigInterface
{
    /**
     * @var string
     */
    private $manualCheck;

    public function __construct(string $manualCheck)
    {
        $this->manualCheck = $manualCheck;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        return (object) [
            'manual_check' => $this->getManualCheck(),
        ];
    }

    /**
     * @return string
     */
    public function getManualCheck(): string
    {
        return $this->manualCheck;
    }
}
