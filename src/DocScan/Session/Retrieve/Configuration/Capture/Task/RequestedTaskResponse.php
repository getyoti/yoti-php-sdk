<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Task;

abstract class RequestedTaskResponse
{
    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var string|null
     */
    protected $state;

    /**
     * Returns the type of the {@link RequestedTaskResponse}
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Returns the current state of the Requested Task
     *
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }
}
