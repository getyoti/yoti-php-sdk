<?php

namespace Yoti\Test\IDV\Session\Create\Check;

use PHPUnit\Framework\Assert;
use Yoti\IDV\Session\Create\Check\Advanced\RequestedExactMatchingStrategy;
use Yoti\IDV\Session\Create\Check\Advanced\RequestedSearchProfileSources;
use Yoti\IDV\Session\Create\Check\RequestedYotiAccountWatchlistAdvancedCaConfigBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Create\Check\RequestedYotiAccountWatchlistAdvancedCaConfigBuilder
 */
class RequestedYotiAccountWatchlistAdvancedCaConfigTest extends TestCase
{
    private const SOME_REMOVE_DECEASED = true;
    private const SOME_SHARE_URL = false;

    /**
     * @test
     * @covers ::build
     * @covers \Yoti\IDV\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfigBuilder::withMatchingStrategy
     * @covers \Yoti\IDV\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfigBuilder::withSources
     * @covers \Yoti\IDV\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfigBuilder::withShareUrl
     * @covers \Yoti\IDV\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfigBuilder::withRemoveDeceased
     * @covers \Yoti\IDV\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfigBuilder::build
     * @covers \Yoti\IDV\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfig::getType
     * @covers \Yoti\IDV\Session\Create\Check\RequestedYotiAccountWatchlistAdvancedCaConfig::getType
     */
    public function builderShouldBuildWithCorrectProperties(): void
    {
        $exactMatchingStrategy = new RequestedExactMatchingStrategy();
        $profileSource = new RequestedSearchProfileSources('some_string');

        $result = (new RequestedYotiAccountWatchlistAdvancedCaConfigBuilder())
            ->withRemoveDeceased(self::SOME_REMOVE_DECEASED)
            ->withShareUrl(self::SOME_SHARE_URL)
            ->withSources($profileSource)
            ->withMatchingStrategy($exactMatchingStrategy)
            ->build();

        Assert::assertNotNull($result);
        Assert::assertEquals(self::SOME_REMOVE_DECEASED, $result->getRemoveDeceased());
        Assert::assertEquals(self::SOME_SHARE_URL, $result->getShareUrl());
        Assert::assertEquals($profileSource, $result->getSources());
        Assert::assertEquals($exactMatchingStrategy, $result->getMatchingStrategy());
        Assert::assertEquals('WITH_YOTI_ACCOUNT', $result->getType());
    }
}
