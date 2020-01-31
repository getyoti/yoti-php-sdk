<?php

namespace Yoti\DocScan\Session\Retrieve;

class IdDocumentResourceResponse extends ResourceResponse
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
     * DocumentResourceResponse constructor.
     * @param array<string, mixed> $idDocument
     */
    public function __construct(array $idDocument)
    {
        parent::__construct($idDocument);

        $this->documentType = $idDocument['document_type'] ?? null;
        $this->issuingCountry = $idDocument['issuing_country'] ?? null;

        if (isset($idDocument['pages'])) {
            foreach ($idDocument['pages'] as $page) {
                $this->pages[] = new PageResponse($page);
            }
        }

        $this->documentFields = isset($idDocument['document_fields'])
            ? new DocumentFieldsResponse($idDocument['document_fields'])
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
}
