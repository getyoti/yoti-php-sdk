<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document;

class ObjectiveResponse
{
    /**
     * @var string
     */
    private $type;

    /**
     * Returns the objective type as a String
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
