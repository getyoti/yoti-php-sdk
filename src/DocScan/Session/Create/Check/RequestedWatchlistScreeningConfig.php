<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

/**
 * Class RequestedWatchlistScreeningConfig
 * @package Yoti\DocScan\Session\Create\Check
 */
class RequestedWatchlistScreeningConfig implements RequestedCheckConfigInterface
{
    /**
     * @var string[]
     */
    private $categories;

    /**
     * RequestedWatchlistScreeningConfig constructor.
     * @param string[] $categories
     */
    public function __construct(array $categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'categories' => $this->categories,
        ];
    }

    /**
     * @return string[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }
}
