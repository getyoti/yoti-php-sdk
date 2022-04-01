<?php

namespace Yoti\DocScan\Session\Instructions\Document;

abstract class SelectedDocument
{
    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * The type of document that will be used to satisfy the document requirement
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
