<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document;

class SupportedDocumentResponse
{
    /**
     * @var string
     */
    private $type;

    /**
     * Returns the type of document that is supported.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
