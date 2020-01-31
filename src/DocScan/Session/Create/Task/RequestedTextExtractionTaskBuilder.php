<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Task;

use Yoti\Util\Validation;

class RequestedTextExtractionTaskBuilder
{

    private const ALWAYS = 'ALWAYS';
    private const FALLBACK = 'FALLBACK';
    private const NEVER = 'NEVER';

    /**
     * @var string
     */
    private $manualCheck;

    public function withManualCheckAlways(): self
    {
        return $this->withManualCheck(self::ALWAYS);
    }

    public function withManualCheck(string $manualCheck): self
    {
        $this->manualCheck = $manualCheck;
        return $this;
    }

    public function withManualCheckFallback(): self
    {
        return $this->withManualCheck(self::FALLBACK);
    }

    public function withManualCheckNever(): self
    {
        return $this->withManualCheck(self::NEVER);
    }

    public function build(): RequestedTextExtractionTask
    {
        Validation::notEmptyString($this->manualCheck, 'manualCheck');

        $config = new RequestedTextExtractionTaskConfig($this->manualCheck);
        return new RequestedTextExtractionTask($config);
    }
}
