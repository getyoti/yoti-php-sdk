<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\DocScan\Constants;
use Yoti\Util\Validation;

class RequestedWatchlistScreeningConfigBuilder
{
    /**
     * @var string[]
     */
    private $categories;

    /**
     * @return $this
     */
    public function withAdverseMediaCategory(): RequestedWatchlistScreeningConfigBuilder
    {
        return $this->withCategory(Constants::ADVERSE_MEDIA);
    }

    /**
     * @return $this|RequestedWatchlistScreeningConfigBuilder
     */
    public function withSanctionsCategory(): RequestedWatchlistScreeningConfigBuilder
    {
        return $this->withCategory(Constants::SANCTIONS);
    }

    /**
     * @param string $category
     * @return $this
     */
    public function withCategory(string $category): RequestedWatchlistScreeningConfigBuilder
    {
        Validation::notNull($category, 'category');
        $this->categories[] = $category;

        return $this;
    }

    /**
     * @return RequestedWatchlistScreeningConfig
     */
    public function build(): RequestedWatchlistScreeningConfig
    {
        $categories = $this->categories ?? [];
        return new RequestedWatchlistScreeningConfig($categories);
    }
}
