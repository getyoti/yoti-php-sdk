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
     * @var bool|null
     */
    private $allowExpiredDocuments;

    /**
     * @param string $inclusion
     * @param DocumentRestriction[] $documents
     * @param bool|null $allowNonLatinDocuments
     * @param bool|null $allowExpiredDocuments
     */
    public function __construct(
        string $inclusion,
        array $documents,
        ?bool $allowNonLatinDocuments,
        ?bool $allowExpiredDocuments
    ) {
        parent::__construct(Constants::DOCUMENT_RESTRICTIONS);

        Validation::notEmptyString($inclusion, 'inclusion');
        $this->inclusion = $inclusion;

        Validation::notEmptyArray($documents, 'documents');
        Validation::isArrayOfType($documents, [DocumentRestriction::class], 'documents');
        $this->documents = $documents;
        $this->allowNonLatinDocuments = $allowNonLatinDocuments;
        $this->allowExpiredDocuments = $allowExpiredDocuments;
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

        if (isset($this->allowExpiredDocuments)) {
            $jsonData->allow_expired_documents = $this->allowExpiredDocuments;
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

    /**
     * @return bool|null
     */
    public function isAllowExpiredDocuments(): ?bool
    {
        return $this->allowExpiredDocuments;
    }
}
