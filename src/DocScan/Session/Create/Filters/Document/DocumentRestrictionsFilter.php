<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters\Document;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Create\Filters\DocumentFilter;
use Yoti\Util\Validation;

class DocumentRestrictionsFilter extends DocumentFilter implements \JsonSerializable
{
    /**
     * @var string
     */
    private $inclusion;

    /**
     * @var DocumentRestriction[]
     */
    private $documents;

    /**
     * @var bool|null
     */
    private $allowNonLatinDocuments;

    /**
     * @param string $inclusion
     * @param DocumentRestriction[] $documents
     * @param bool|null $allowNonLatinDocuments
     */
    public function __construct(string $inclusion, array $documents, ?bool $allowNonLatinDocuments)
    {
        parent::__construct(Constants::DOCUMENT_RESTRICTIONS);

        Validation::notEmptyString($inclusion, 'inclusion');
        $this->inclusion = $inclusion;

        Validation::notEmptyArray($documents, 'documents');
        Validation::isArrayOfType($documents, [DocumentRestriction::class], 'documents');
        $this->documents = $documents;
        $this->allowNonLatinDocuments = $allowNonLatinDocuments;
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        $jsonData = parent::jsonSerialize();
        $jsonData->inclusion = $this->inclusion;
        $jsonData->documents = $this->documents;

        if (isset($this->allowNonLatinDocuments)) {
            $jsonData->allow_non_latin_documents = $this->allowNonLatinDocuments;
        }

        return $jsonData;
    }

    /**
     * @return bool|null
     */
    public function isAllowNonLatinDocuments(): ?bool
    {
        return $this->allowNonLatinDocuments;
    }
}
