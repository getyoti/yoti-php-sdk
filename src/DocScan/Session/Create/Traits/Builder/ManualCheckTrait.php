<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Traits\Builder;

use Yoti\DocScan\Constants;

trait ManualCheckTrait
{
    /**
     * @var string
     */
    private $manualCheck;

    /**
     * @param string $manualCheck
     *
     * @return $this
     */
    private function setManualCheck(string $manualCheck): self
    {
        $this->manualCheck = $manualCheck;
        return $this;
    }

    /**
     * @return $this
     */
    public function withManualCheckAlways(): self
    {
        return $this->setManualCheck(Constants::ALWAYS);
    }

    /**
     * @return $this
     */
    public function withManualCheckFallback(): self
    {
        return $this->setManualCheck(Constants::FALLBACK);
    }

    /**
     * @return $this
     */
    public function withManualCheckNever(): self
    {
        return $this->setManualCheck(Constants::NEVER);
    }
}
