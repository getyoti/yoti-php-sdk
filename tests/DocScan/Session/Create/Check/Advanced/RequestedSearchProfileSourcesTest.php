<?php

namespace Yoti\Test\IDV\Session\Create\Check\Advanced;

use PHPUnit\Framework\Assert;
use Yoti\IDV\Session\Create\Check\Advanced\RequestedSearchProfileSourcesBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Create\Check\Advanced\RequestedSearchProfileSourcesBuilder
 */
class RequestedSearchProfileSourcesTest extends TestCase
{
    /**
     * @var string
     */
    private const SOME_SEARCH_PROFILE = "someSearchProfile";

    /**
     * @test
     * @covers ::build
     * @covers ::withSearchProfile
     * @covers \Yoti\IDV\Session\Create\Check\Advanced\RequestedSearchProfileSources::getSearchProfile
     * @covers \Yoti\IDV\Session\Create\Check\Advanced\RequestedSearchProfileSources::getType
     * @covers \Yoti\IDV\Session\Create\Check\Contracts\Advanced\RequestedCaSources::getType
     * @covers \Yoti\IDV\Session\Create\Check\Advanced\RequestedSearchProfileSources::__construct
     */
    public function builderShouldBuildWithCorrectSearchProfile(): void
    {
        $result = (new RequestedSearchProfileSourcesBuilder())
            ->withSearchProfile(self::SOME_SEARCH_PROFILE)
            ->build();

        Assert::assertNotNull($result);
        Assert::assertEquals(self::SOME_SEARCH_PROFILE, $result->getSearchProfile());
        Assert::assertEquals('PROFILE', $result->getType());
    }

    /**
     * @test
     * @covers ::build
     * @covers ::withSearchProfile
     * @covers \Yoti\IDV\Session\Create\Check\Advanced\RequestedSearchProfileSources::getSearchProfile
     * @covers \Yoti\IDV\Session\Create\Check\Advanced\RequestedSearchProfileSources::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\Check\Advanced\RequestedSearchProfileSources::__construct
     * @covers \Yoti\IDV\Session\Create\Check\Contracts\Advanced\RequestedCaSources::jsonSerialize
     */
    public function builderShouldBuildWithCorrectJson(): void
    {
        $result = (new RequestedSearchProfileSourcesBuilder())
            ->withSearchProfile(self::SOME_SEARCH_PROFILE)
            ->build();

        $expected = [
            'type' => 'PROFILE',
            'search_profile' => self::SOME_SEARCH_PROFILE
        ];

        Assert::assertEquals(json_encode($expected), json_encode($result));
    }
}
