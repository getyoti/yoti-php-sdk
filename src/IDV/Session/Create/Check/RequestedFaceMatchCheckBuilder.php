<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Create\Check;

use Yoti\IDV\Session\Create\Traits\Builder\ManualCheckTrait;
use Yoti\Util\Validation;

class RequestedFaceMatchCheckBuilder
{
    use ManualCheckTrait;

    public function build(): RequestedFaceMatchCheck
    {
        Validation::notEmptyString($this->manualCheck, 'manualCheck');

        $config = new RequestedFaceMatchCheckConfig($this->manualCheck);
        return new RequestedFaceMatchCheck($config);
    }
}
