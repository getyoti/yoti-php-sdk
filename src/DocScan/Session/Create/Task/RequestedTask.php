<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Task;

use JsonSerializable;
use stdClass;
use Yoti\Util\Json;

abstract class RequestedTask implements JsonSerializable
{
    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        return (object)Json::withoutNullValues([
            'type' => $this->getType(),
            'config' => $this->getConfig()
        ]);
    }

    /**
     * @return string
     */
    abstract protected function getType(): string;

    /**
     * @return RequestedTaskConfigInterface|null
     */
    abstract protected function getConfig(): ?RequestedTaskConfigInterface;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return Json::encode($this);
    }
}
