<?php

namespace Yoti\Test\DocScan\Session\Create\Check\Advanced;

use PHPUnit\Framework\Assert;
use Yoti\DocScan\Session\Create\Check\Advanced\RequestedTypeListSourcesBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Check\Advanced\RequestedTypeListSourcesBuilder
 */
class RequestedTypeListSourcesTest extends TestCase
{
    /**
     * @var string[]
     */
    private const SOME_TYPES = ['someType', 'someOtherType'];

    /**
     * @test
     * @covers ::build
     * @covers ::withTypes
     * @covers \Yoti\DocScan\Session\Create\Check\Advanced\RequestedTypeListSources::getTypes
     * @covers \Yoti\DocScan\Session\Create\Check\Advanced\RequestedTypeListSources::getType
     * @covers \Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaSources::getType
     * @covers \Yoti\DocScan\Session\Create\Check\Advanced\RequestedTypeListSources::__construct
     */
    public function builderShouldBuildWithCorrectListOfTypes(): void
    {
        $result = (new RequestedTypeListSourcesBuilder())
            ->withTypes(self::SOME_TYPES)
            ->build();

        Assert::assertNotNull($result);
        Assert::assertEquals(self::SOME_TYPES, $result->getTypes());
        Assert::assertEquals('TYPE_LIST', $result->getType());
    }

    /**
     * @test
     * @covers ::build
     * @covers ::withTypes
     * @covers \Yoti\DocScan\Session\Create\Check\Advanced\RequestedTypeListSources::getTypes
     * @covers \Yoti\DocScan\Session\Create\Check\Advanced\RequestedTypeListSources::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaSources::jsonSerialize
     */
    public function builderShouldBuildWithCorrectJson(): void
    {
        $result = (new RequestedTypeListSourcesBuilder())
            ->withTypes(self::SOME_TYPES)
            ->build();

        $expected = [
            'type' => 'TYPE_LIST',
            'types' => self::SOME_TYPES
        ];

        Assert::assertEquals(json_encode($expected), json_encode($result));
    }
}
