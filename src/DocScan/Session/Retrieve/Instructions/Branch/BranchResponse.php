<?php

namespace Yoti\DocScan\Session\Retrieve\Instructions\Branch;

class BranchResponse
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
