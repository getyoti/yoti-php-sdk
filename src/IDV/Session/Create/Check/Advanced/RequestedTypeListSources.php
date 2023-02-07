<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Create\Check\Advanced;

use stdClass;
use Yoti\IDV\Constants;
use Yoti\IDV\Session\Create\Check\Contracts\Advanced\RequestedCaSources;

class RequestedTypeListSources extends RequestedCaSources
{
    /**
     * @var string[]
     */
    private $types;

    /**
     * RequestedTypeListSources constructor.
     * @param string[] $types
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * @return string[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return Constants::TYPE_LIST;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        $json = parent::jsonSerialize();
        $json->types = $this->getTypes();

        return $json;
    }
}
