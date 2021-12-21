<?php

namespace Yoti\Test\DocScan\Session\Create\Check;

use PHPUnit\Framework\Assert;
use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfig;
use Yoti\DocScan\Session\Create\Check\RequestedWatchlistAdvancedCaCheckBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Check\RequestedWatchlistAdvancedCaCheck
 */
class RequestedWatchlistAdvancedCaCheckTest extends TestCase
{
    /**
     * @test
     * @covers ::getConfig
     * @covers ::getType
     * @covers ::__construct
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedWatchlistAdvancedCaCheckBuilder::build
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedWatchlistAdvancedCaCheckBuilder::withConfig
     */
    public function builderShouldBuildWithoutAnySuppliedConfig(): void
    {
        $configMock = $this->createMock(RequestedWatchlistAdvancedCaConfig::class);
        $check = (new RequestedWatchlistAdvancedCaCheckBuilder())
            ->withConfig($configMock)
            ->build();

        Assert::assertEquals(Constants::WATCHLIST_ADVANCED_CA, $check->getType());
        Assert::assertInstanceOf(RequestedWatchlistAdvancedCaConfig::class, $check->getConfig());
    }
}
