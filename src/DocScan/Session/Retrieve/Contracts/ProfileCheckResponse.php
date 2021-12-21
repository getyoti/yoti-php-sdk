<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve\Contracts;

use Yoti\DocScan\Session\Retrieve\CheckResponse;
use Yoti\DocScan\Session\Retrieve\GeneratedProfileResponse;

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
