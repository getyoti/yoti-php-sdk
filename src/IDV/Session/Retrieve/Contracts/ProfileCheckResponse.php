<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Retrieve\Contracts;

use Yoti\IDV\Session\Retrieve\CheckResponse;
use Yoti\IDV\Session\Retrieve\GeneratedProfileResponse;

abstract class ProfileCheckResponse extends CheckResponse
{
    /**
     * @var GeneratedProfileResponse
     */
    private $generatedProfile;

    /**
     * @return GeneratedProfileResponse
     */
    public function getGeneratedProfile(): GeneratedProfileResponse
    {
        return $this->generatedProfile;
    }
}
