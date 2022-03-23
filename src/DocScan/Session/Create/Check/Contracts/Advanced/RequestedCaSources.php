<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check\Contracts\Advanced;

use stdClass;
use Yoti\Util\Json;

abstract class RequestedCaSources implements \JsonSerializable
{
    /**
     * @var string
     */
    public $type;

    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        return (object)Json::withoutNullValues([
            'type' => $this->getType(),
        ]);
    }
}
