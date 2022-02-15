<?php

namespace Yoti\Test\DocScan\Session\Create\Check;

use PHPUnit\Framework\Assert;
use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningCheckBuilder;
use Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningConfigBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningCheck
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
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningCheckBuilder::build
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningCheckBuilder::withConfig
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
