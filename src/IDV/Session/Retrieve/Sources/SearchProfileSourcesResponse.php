<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Retrieve\Sources;

use Yoti\IDV\Constants;
use Yoti\IDV\Session\Retrieve\Contracts\CaSourcesResponse;

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
