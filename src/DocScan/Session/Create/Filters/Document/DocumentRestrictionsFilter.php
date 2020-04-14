<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters\Document;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Create\Filters\RequiredDocumentFilter;
use Yoti\Util\Validation;

class DocumentRestrictionsFilter extends RequiredDocumentFilter implements \JsonSerializable
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
     * @param string $inclusion
     * @param DocumentRestriction[] $documents
     */
    public function __construct(string $inclusion, array $documents)
    {
        parent::__construct(Constants::DOCUMENT_RESTRICTIONS);

        $this->inclusion = $inclusion;

        Validation::isArrayOfType($documents, [DocumentRestriction::class], 'documents');
        $this->documents = $documents;
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        $jsonData = parent::jsonSerialize();
        $jsonData->inclusion = $this->inclusion;
        $jsonData->documents = $this->documents;
        return $jsonData;
    }
}
