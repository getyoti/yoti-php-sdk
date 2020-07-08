<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Task;

use Yoti\Util\Json;

class RequestedTextExtractionTaskConfig implements RequestedTaskConfigInterface
{

    /**
     * @var string
     */
    private $manualCheck;

    /**
     * @var string|null
     */
    private $chipData;

    /**
     * @param string $manualCheck
     * @param string|null $chipData
     */
    public function __construct(string $manualCheck, ?string $chipData = null)
    {
        $this->manualCheck = $manualCheck;
        $this->chipData = $chipData;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return Json::withoutNullValues([
            'manual_check' => $this->getManualCheck(),
            'chip_data' => $this->getChipData(),
        ]);
    }

    /**
     * @return string
     */
    public function getManualCheck(): string
    {
        return $this->manualCheck;
    }

    /**
     * @return string
     */
    public function getChipData(): ?string
    {
        return $this->chipData;
    }
}
