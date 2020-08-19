<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Task;

use Yoti\Util\Json;

class RequestedSupplementaryDocTextExtractionTaskConfig implements RequestedTaskConfigInterface
{
    /**
     * @var string
     */
    private $manualCheck;

    /**
     * @param string $manualCheck
     */
    public function __construct(string $manualCheck)
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
     * @return string
     */
    public function getManualCheck(): string
    {
        return $this->manualCheck;
    }
}
