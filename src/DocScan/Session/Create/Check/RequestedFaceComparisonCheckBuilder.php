<?php

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\DocScan\Session\Create\Traits\Builder\ManualCheckTrait;
use Yoti\Util\Validation;

class RequestedFaceComparisonCheckBuilder
{
    use ManualCheckTrait;

    public function build(): RequestedFaceComparisonCheck
    {
        Validation::notEmptyString($this->manualCheck, 'manualCheck');

        $config = new RequestedFaceComparisonCheckConfig($this->manualCheck);
        return new RequestedFaceComparisonCheck($config);
    }
}
