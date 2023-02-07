<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Create\Task;

use Yoti\IDV\Session\Create\Traits\Builder\ManualCheckTrait;
use Yoti\Util\Validation;

class RequestedSupplementaryDocTextExtractionTaskBuilder
{
    use ManualCheckTrait;

    /**
     * @return RequestedSupplementaryDocTextExtractionTask
     */
    public function build(): RequestedSupplementaryDocTextExtractionTask
    {
        Validation::notEmptyString($this->manualCheck, 'manualCheck');

        $config = new RequestedSupplementaryDocTextExtractionTaskConfig($this->manualCheck);
        return new RequestedSupplementaryDocTextExtractionTask($config);
    }
}
