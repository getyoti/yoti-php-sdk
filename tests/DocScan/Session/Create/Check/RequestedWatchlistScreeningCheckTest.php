<?php

namespace Yoti\Test\IDV\Session\Create\Check;

use PHPUnit\Framework\Assert;
use Yoti\IDV\Constants;
use Yoti\IDV\Session\Create\Check\RequestedWatchlistScreeningCheckBuilder;
use Yoti\IDV\Session\Create\Check\RequestedWatchlistScreeningConfigBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Create\Check\RequestedWatchlistScreeningCheck
 */
class RequestedWatchlistScreeningCheckTest extends TestCase
{
    /**
     * @test
     * @covers ::getConfig
     * @covers ::getType
     */
    public function builderShouldBuildWithoutAnySuppliedConfig(): void
    {
        $check = (new RequestedWatchlistScreeningCheckBuilder())->build();

        Assert::assertEquals(Constants::WATCHLIST_SCREENING, $check->getType());
        Assert::assertNull($check->getConfig());
    }

    /**
     * @test
     * @covers ::getConfig
     * @covers ::__construct
     * @covers ::getType
     * @covers \Yoti\IDV\Session\Create\Check\RequestedWatchlistScreeningCheckBuilder::build
     * @covers \Yoti\IDV\Session\Create\Check\RequestedWatchlistScreeningCheckBuilder::withConfig
     */
    public function builderShouldBuildWithSuppliedConfig(): void
    {
        $config = (new RequestedWatchlistScreeningConfigBuilder())->build();
        $check = (new RequestedWatchlistScreeningCheckBuilder())
            ->withConfig($config)
            ->build();

        Assert::assertEquals(Constants::WATCHLIST_SCREENING, $check->getType());
        Assert::assertEquals($config, $check->getConfig());
    }
}
