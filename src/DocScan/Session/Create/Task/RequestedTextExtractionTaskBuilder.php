<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Task;

use Yoti\DocScan\Session\Create\Traits\Builder\ManualCheckTrait;
use Yoti\Util\Validation;

class RequestedTextExtractionTaskBuilder
{
    use ManualCheckTrait;

    private const DESIRED = 'DESIRED';
    private const IGNORE = 'IGNORE';

    /**
     * @var string
     */
    private $chipData;

    /**
     * @param string $chipData
     *
     * @return $this
     */
    private function withChipData(string $chipData): self
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
     * @return RequestedTextExtractionTask
     */
    public function build(): RequestedTextExtractionTask
    {
        Validation::notEmptyString($this->manualCheck, 'manualCheck');

        $config = new RequestedTextExtractionTaskConfig($this->manualCheck, $this->chipData);
        return new RequestedTextExtractionTask($config);
    }
}
