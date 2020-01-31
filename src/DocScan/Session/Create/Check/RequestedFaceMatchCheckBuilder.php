<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\Util\Validation;

class RequestedFaceMatchCheckBuilder
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

    private function withManualCheck(string $manualCheck): self
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

    public function build(): RequestedFaceMatchCheck
    {
        Validation::notEmptyString($this->manualCheck, 'manualCheck');

        $config = new RequestedFaceMatchCheckConfig($this->manualCheck);
        return new RequestedFaceMatchCheck($config);
    }
}
