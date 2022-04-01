<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve\Sources;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Retrieve\Contracts\CaSourcesResponse;

class SearchProfileSourcesResponse extends CaSourcesResponse
{
    /**
     * @var string
     */
    private $searchProfile;

    /**
     * @param string $searchProfile
     */
    public function __construct(string $searchProfile)
    {
        $this->type = Constants::PROFILE;
        $this->searchProfile = $searchProfile;
    }

    /**
     * @return string
     */
    public function getSearchProfile(): string
    {
        return $this->searchProfile;
    }
}
