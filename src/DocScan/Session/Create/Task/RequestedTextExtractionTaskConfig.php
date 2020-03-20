<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Task;

class RequestedTextExtractionTaskConfig implements RequestedTaskConfigInterface
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
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
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
