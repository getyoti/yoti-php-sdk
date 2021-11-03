<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Task;

abstract class RequestedTaskResponse
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $state;

    /**
     * Returns the type of the {@link RequestedTaskResponse}
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Returns the current state of the Requested Task
     *
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }
}
