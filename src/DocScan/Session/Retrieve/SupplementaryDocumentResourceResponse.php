<?php

namespace Yoti\DocScan\Session\Retrieve;

class SupplementaryDocumentResourceResponse extends ResourceResponse
{
    /**
     * @var string|null
     */
    private $documentType;

    /**
     * @var string|null
     */
    private $issuingCountry;

    /**
     * @var PageResponse[]
     */
    private $pages = [];

    /**
     * @var DocumentFieldsResponse|null
     */
    private $documentFields;

    /**
     * @var FileResponse|null
     */
    private $documentFile;

    /**
     * @param array<string, mixed> $document
     */
    public function __construct(array $document)
    {
        parent::__construct($document);

        $this->documentType = $document['document_type'] ?? null;
        $this->issuingCountry = $document['issuing_country'] ?? null;

        if (isset($document['pages'])) {
            foreach ($document['pages'] as $page) {
                $this->pages[] = new PageResponse($page);
            }
        }

        $this->documentFields = isset($document['document_fields'])
            ? new DocumentFieldsResponse($document['document_fields'])
            : null;

        $this->documentFile = isset($document['file'])
            ? new FileResponse($document['file'])
            : null;
    }

    /**
     * @return string|null
     */
    public function getDocumentType(): ?string
    {
        return $this->documentType;
    }

    /**
     * @return string|null
     */
    public function getIssuingCountry(): ?string
    {
        return $this->issuingCountry;
    }

    /**
     * @return PageResponse[]
     */
    public function getPages(): array
    {
        return $this->pages;
    }

    /**
     * @return DocumentFieldsResponse|null
     */
    public function getDocumentFields(): ?DocumentFieldsResponse
    {
        return $this->documentFields;
    }

    /**
     * @return FileResponse|null
     */
    public function getDocumentFile(): ?FileResponse
    {
        return $this->documentFile;
    }

    /**
     * @return SupplementaryDocTextExtractionTaskResponse[]
     */
    public function getTextExtractionTasks(): array
    {
        return $this->filterTasksByType(SupplementaryDocTextExtractionTaskResponse::class);
    }
}
