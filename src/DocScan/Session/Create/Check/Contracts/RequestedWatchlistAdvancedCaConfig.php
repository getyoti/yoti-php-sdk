<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check\Contracts;

use stdClass;
use Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaMatchingStrategy;
use Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaSources;
use Yoti\DocScan\Session\Create\Check\RequestedCheckConfigInterface;

abstract class RequestedWatchlistAdvancedCaConfig implements RequestedCheckConfigInterface
{
    /**
     * @var bool
     */
    private $removeDeceased;

    /**
     * @var bool
     */
    private $shareUrl;

    /**
     * @var RequestedCaSources
     */
    private $sources;

    /**
     * @var RequestedCaMatchingStrategy
     */
    private $matchingStrategy;

    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @param bool $removeDeceased
     * @param bool $shareUrl
     * @param RequestedCaSources $sources
     * @param RequestedCaMatchingStrategy $matchingStrategy
     */
    public function __construct(
        bool $removeDeceased,
        bool $shareUrl,
        RequestedCaSources $sources,
        RequestedCaMatchingStrategy $matchingStrategy
    ) {
        $this->removeDeceased = $removeDeceased;
        $this->shareUrl = $shareUrl;
        $this->sources = $sources;
        $this->matchingStrategy = $matchingStrategy;
    }

    /**
     * @return bool
     */
    public function getRemoveDeceased(): bool
    {
        return $this->removeDeceased;
    }

    /**
     * @return bool
     */
    public function getShareUrl(): bool
    {
        return $this->shareUrl;
    }

    /**
     * @return RequestedCaSources
     */
    public function getSources(): RequestedCaSources
    {
        return $this->sources;
    }

    /**
     * @return RequestedCaMatchingStrategy
     */
    public function getMatchingStrategy(): RequestedCaMatchingStrategy
    {
        return $this->matchingStrategy;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        return (object)[
            'remove_deceased' => $this->getRemoveDeceased(),
            'share_url' => $this->getShareUrl(),
            'sources' => $this->getSources(),
            'matching_strategy' => $this->getMatchingStrategy(),
            'type' => $this->getType(),
        ];
    }
}
