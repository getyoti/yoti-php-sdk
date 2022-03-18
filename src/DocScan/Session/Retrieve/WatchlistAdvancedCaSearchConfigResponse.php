<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Retrieve\Contracts\CaMatchingStrategyResponse;
use Yoti\DocScan\Session\Retrieve\Contracts\CaSourcesResponse;
use Yoti\DocScan\Session\Retrieve\Contracts\WatchlistSearchConfigResponse;
use Yoti\DocScan\Session\Retrieve\Matching\ExactMatchingStrategyResponse;
use Yoti\DocScan\Session\Retrieve\Matching\FuzzyMatchingStrategyResponse;
use Yoti\DocScan\Session\Retrieve\Sources\SearchProfileSourcesResponse;
use Yoti\DocScan\Session\Retrieve\Sources\TypeListSourcesResponse;

class WatchlistAdvancedCaSearchConfigResponse extends WatchlistSearchConfigResponse
{
    /**
     * @var string $type
     */
    private $type;

    /**
     * @var bool
     */
    private $removeDeceased;

    /**
     * @var bool
     */
    private $shareUrl;

    /**
     * @var CaSourcesResponse
     */
    private $sources;

    /**
     * @var CaMatchingStrategyResponse
     */
    private $matchingStrategy;

    /**
     * @param array<string, mixed> $searchConfig
     */
    public function __construct(array $searchConfig)
    {
        $this->type = $searchConfig['type'];
        $this->removeDeceased = $searchConfig['remove_deceased'];
        $this->shareUrl = $searchConfig['share_url'];

        $this->setSources($searchConfig);
        $this->setMatchingStrategy($searchConfig);
    }

    /**
     * @param array<string, mixed> $searchConfig
     */
    private function setSources(array $searchConfig): void
    {
        if ($searchConfig['sources']['type'] == Constants::PROFILE) {
            $this->sources = new SearchProfileSourcesResponse($searchConfig['sources']['search_profile']);
        }
        if ($searchConfig['sources']['type'] == Constants::TYPE_LIST) {
            $this->sources = new TypeListSourcesResponse($searchConfig['sources']['types']);
        }
    }

    /**
     * @param array<string, mixed> $searchConfig
     */
    private function setMatchingStrategy(array $searchConfig): void
    {
        if ($searchConfig['matching_strategy']['type'] == Constants::EXACT) {
            $this->matchingStrategy = new ExactMatchingStrategyResponse(
                $searchConfig['matching_strategy']['exact_match']
            );
        }
        if ($searchConfig['matching_strategy']['type'] == Constants::FUZZY) {
            $this->matchingStrategy = new FuzzyMatchingStrategyResponse(
                $searchConfig['matching_strategy']['fuzziness']
            );
        }
    }

    /**
     * @return bool
     */
    public function isRemoveDeceased(): bool
    {
        return $this->removeDeceased;
    }

    /**
     * @return bool
     */
    public function isShareUrl(): bool
    {
        return $this->shareUrl;
    }

    /**
     * @return CaSourcesResponse
     */
    public function getSources(): CaSourcesResponse
    {
        return $this->sources;
    }

    /**
     * @return CaMatchingStrategyResponse
     */
    public function getMatchingStrategy(): CaMatchingStrategyResponse
    {
        return $this->matchingStrategy;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
