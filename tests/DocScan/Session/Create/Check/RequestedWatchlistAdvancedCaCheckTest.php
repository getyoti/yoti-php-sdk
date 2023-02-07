<?php

namespace Yoti\Test\IDV\Session\Create\Check;

use PHPUnit\Framework\Assert;
use Yoti\IDV\Constants;
use Yoti\IDV\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfig;
use Yoti\IDV\Session\Create\Check\RequestedWatchlistAdvancedCaCheckBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Create\Check\RequestedWatchlistAdvancedCaCheck
 */
class RequestedWatchlistAdvancedCaCheckTest extends TestCase
{
    /**
     * @test
     * @covers ::getConfig
     * @covers ::getType
     * @covers ::__construct
     * @covers \Yoti\IDV\Session\Create\Check\RequestedWatchlistAdvancedCaCheckBuilder::build
     * @covers \Yoti\IDV\Session\Create\Check\RequestedWatchlistAdvancedCaCheckBuilder::withConfig
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
