<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Task;

use JsonSerializable;
use Yoti\Util\Json;

abstract class RequestedTask implements JsonSerializable
{

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return Json::withoutNullValues([
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
