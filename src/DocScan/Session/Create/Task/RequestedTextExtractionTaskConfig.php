<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Task;

use stdClass;
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
     * @var bool|null
     */
    private $createExpandedDocumentFields;

    /**
     * Constructor.
     *
     * @param string      $manualCheck               // Consider adding type hints for clarity and type safety.
     * @param string|null $chipData                  // Consider adding type hints for clarity and type safety.
     * @param bool|null   $createExpandedDocumentFields // Consider adding type hints for clarity and type safety.
     */
    public function __construct(
        string $manualCheck,
        ?string $chipData = null,
        ?bool $createExpandedDocumentFields = false
    ) {
        $this->manualCheck = $manualCheck;
        $this->chipData = $chipData;
        $this->createExpandedDocumentFields = $createExpandedDocumentFields;
    }

    /**
     * Serializes the object to JSON.
     *
     * @return stdClass
     */
    public function jsonSerialize(): stdClass // Ensure consistency in return types nullability.
    {
        return (object) Json::withoutNullValues([
            'manual_check' => $this->getManualCheck(),
            'chip_data' => $this->getChipData(),
            'create_expanded_document_fields' => $this->getCreateExpandedDocumentFields(),
        ]);
    }

    /**
     * Get the manual check value.
     *
     * @return string
     */
    public function getManualCheck(): string
    {
        return $this->manualCheck;
    }

    /**
     * Get the chip data.
     *
     * @return string|null
     */
    public function getChipData(): ?string // Ensure consistency in return types nullability.
    {
        return $this->chipData;
    }

    /**
     * Get the value of create expanded document fields.
     *
     * @return bool|null
     */
    public function getCreateExpandedDocumentFields(): ?bool // Ensure consistency in return types nullability.
    {
        return $this->createExpandedDocumentFields;
    }
}
