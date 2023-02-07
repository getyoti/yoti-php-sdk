<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Retrieve\Sources;

use Yoti\IDV\Constants;
use Yoti\IDV\Session\Retrieve\Contracts\CaSourcesResponse;

class TypeListSourcesResponse extends CaSourcesResponse
{
    /**
     * @var string[]
     */
    private $types;

    /**
     * @param string[] $types
     */
    public function __construct(array $types)
    {
        $this->type = Constants::TYPE_LIST;
        $this->types = $types;
    }

    /**
     * @return string[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }
}
