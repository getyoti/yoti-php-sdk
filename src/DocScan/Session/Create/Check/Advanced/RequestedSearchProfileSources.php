<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check\Advanced;

use stdClass;
use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaSources;

class RequestedSearchProfileSources extends RequestedCaSources
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
        $this->searchProfile = $searchProfile;
    }

    /**
     * @return string
     */
    public function getSearchProfile(): string
    {
        return $this->searchProfile;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return Constants::PROFILE;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        $json = parent::jsonSerialize();
        $json->search_profile = $this->getSearchProfile();

        return $json;
    }
}
