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
     * @var DocumentIdPhotoResponse|null
     */
    private $documentIdPhoto;

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

        $this->documentIdPhoto = isset($idDocument['document_id_photo'])
            ? new DocumentIdPhotoResponse($idDocument['document_id_photo'])
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
     * @return DocumentIdPhotoResponse|null
     */
    public function getDocumentIdPhoto(): ?DocumentIdPhotoResponse
    {
        return $this->documentIdPhoto;
    }

    /**
     * @return TextExtractionTaskResponse[]
     */
    public function getTextExtractionTasks(): array
    {
        return $this->filterTasksByType(TextExtractionTaskResponse::class);
    }
}
