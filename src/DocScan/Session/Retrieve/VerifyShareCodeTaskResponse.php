<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class VerifyShareCodeTaskResponse extends TaskResponse
{
    /**
     * VerifyShareCodeTaskResponse constructor.
     * @param array<string, mixed> $task
     */
    public function __construct(array $task)
    {
        parent::__construct($task);
    }
}
