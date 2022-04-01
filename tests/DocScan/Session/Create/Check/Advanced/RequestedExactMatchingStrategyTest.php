<?php

namespace Yoti\Test\DocScan\Session\Create\Check\Advanced;

use PHPUnit\Framework\Assert;
use Yoti\DocScan\Session\Create\Check\Advanced\RequestedExactMatchingStrategyBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Check\Advanced\RequestedExactMatchingStrategyBuilder
 */
class RequestedExactMatchingStrategyTest extends TestCase
{
    /**
     * @test
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Check\Advanced\RequestedExactMatchingStrategy::isExactMatch
     * @covers \Yoti\DocScan\Session\Create\Check\Advanced\RequestedExactMatchingStrategy::getType
     * @covers \Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaMatchingStrategy::getType
     */
    public function builderShouldBuildRequestedExactMatchingStrategyTest(): void
    {
        $result = (new RequestedExactMatchingStrategyBuilder())->build();

        Assert::assertNotNull($result);
        Assert::assertEquals(true, $result->isExactMatch());
        Assert::assertEquals('EXACT', $result->getType());
    }

    /**
     * @test
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Check\Advanced\RequestedExactMatchingStrategy::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaMatchingStrategy::jsonSerialize
     */
    public function builderShouldBuildCorrectJson(): void
    {
        $result = (new RequestedExactMatchingStrategyBuilder())->build();

        $expected = [
            'type' => 'EXACT'
        ];

        Assert::assertEquals(json_encode($expected), json_encode($result));
    }
}
