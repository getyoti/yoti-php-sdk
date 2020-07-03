<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Task;

use Yoti\Util\Validation;

class RequestedTextExtractionTaskBuilder
{

    private const ALWAYS = 'ALWAYS';
    private const FALLBACK = 'FALLBACK';
    private const NEVER = 'NEVER';
    private const DESIRED = 'DESIRED';
    private const IGNORE = 'IGNORE';
    private const MANDATORY = 'MANDATORY';

    /**
     * @var string
     */
    private $manualCheck;

    /**
     * @var string
     */
    private $chipData;

    /**
     * @param string $manualCheck
     *
     * @return $this
     */
    public function withManualCheck(string $manualCheck): self
    {
        $this->manualCheck = $manualCheck;
        return $this;
    }

    /**
     * @return $this
     */
    public function withManualCheckAlways(): self
    {
        return $this->withManualCheck(self::ALWAYS);
    }

    /**
     * @return $this
     */
    public function withManualCheckFallback(): self
    {
        return $this->withManualCheck(self::FALLBACK);
    }

    /**
     * @return $this
     */
    public function withManualCheckNever(): self
    {
        return $this->withManualCheck(self::NEVER);
    }

    /**
     * @param string $chipData
     *
     * @return $this
     */
    public function withChipData(string $chipData): self
    {
        $this->chipData = $chipData;
        return $this;
    }

    /**
     * @return $this
     */
    public function withChipDataDesired(): self
    {
        return $this->withChipData(self::DESIRED);
    }

    /**
     * @return $this
     */
    public function withChipDataIgnore(): self
    {
        return $this->withChipData(self::IGNORE);
    }

    /**
     * @return $this
     */
    public function withChipDataMandatory(): self
    {
        return $this->withChipData(self::MANDATORY);
    }

    /**
     * @return RequestedTextExtractionTask
     */
    public function build(): RequestedTextExtractionTask
    {
        Validation::notEmptyString($this->manualCheck, 'manualCheck');

        $config = new RequestedTextExtractionTaskConfig($this->manualCheck, $this->chipData);
        return new RequestedTextExtractionTask($config);
    }
}
