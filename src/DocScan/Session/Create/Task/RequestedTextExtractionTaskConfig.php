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
     * @param string $manualCheck
     * @param string|null $chipData
     * @param bool|null $createExpandedDocumentFields
     */
    public function __construct(string $manualCheck, ?string $chipData = null, ?bool $createExpandedDocumentFields = false)
    {
        $this->manualCheck = $manualCheck;
        $this->chipData = $chipData;
        $this->createExpandedDocumentFields = $createExpandedDocumentFields;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        return (object)Json::withoutNullValues([
            'manual_check' => $this->getManualCheck(),
            'chip_data' => $this->getChipData(),
            'create_expanded_document_fields' => $this->getCreateExpandedDocumentFields(),
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

     /**
     * @return bool
     */
    public function getCreateExpandedDocumentFields(): ?bool
    {
        return $this->createExpandedDocumentFields;
    }
}
