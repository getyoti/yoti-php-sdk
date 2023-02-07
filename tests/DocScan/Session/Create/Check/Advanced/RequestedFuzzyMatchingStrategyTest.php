<?php

namespace Yoti\Test\IDV\Session\Create\Check\Advanced;

use PHPUnit\Framework\Assert;
use Yoti\IDV\Session\Create\Check\Advanced\RequestedFuzzyMatchingStrategyBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Create\Check\Advanced\RequestedFuzzyMatchingStrategyBuilder
 */
class RequestedFuzzyMatchingStrategyTest extends TestCase
{
    /**
     * @var float
     */
    private const SOME_FUZZINESS = 0.56;

    /**
     * @test
     * @covers ::build
     * @covers ::withFuzziness
     * @covers \Yoti\IDV\Session\Create\Check\Advanced\RequestedFuzzyMatchingStrategy::getFuzziness
     * @covers \Yoti\IDV\Session\Create\Check\Advanced\RequestedFuzzyMatchingStrategy::getType
     * @covers \Yoti\IDV\Session\Create\Check\Contracts\Advanced\RequestedCaMatchingStrategy::getType
     * @covers \Yoti\IDV\Session\Create\Check\Advanced\RequestedFuzzyMatchingStrategy::__construct
     */
    public function builderShouldBuildWithCorrectFuzziness(): void
    {
        $result = (new RequestedFuzzyMatchingStrategyBuilder())
            ->withFuzziness(self::SOME_FUZZINESS)
            ->build();

        Assert::assertNotNull($result);
        Assert::assertEquals(self::SOME_FUZZINESS, $result->getFuzziness());
        Assert::assertEquals('FUZZY', $result->getType());
    }

    /**
     * @test
     * @covers ::build
     * @covers ::withFuzziness
     * @covers \Yoti\IDV\Session\Create\Check\Advanced\RequestedFuzzyMatchingStrategy::__construct
     * @covers \Yoti\IDV\Session\Create\Check\Advanced\RequestedFuzzyMatchingStrategy::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\Check\Contracts\Advanced\RequestedCaMatchingStrategy::jsonSerialize
     */
    public function builderShouldBuildCorrectJson(): void
    {
        $result = (new RequestedFuzzyMatchingStrategyBuilder())
            ->withFuzziness(self::SOME_FUZZINESS)
            ->build();

        $expected = [
          'type' => 'FUZZY',
          'fuzziness' => self::SOME_FUZZINESS
        ];

        Assert::assertEquals(json_encode($expected), json_encode($result));
    }
}
