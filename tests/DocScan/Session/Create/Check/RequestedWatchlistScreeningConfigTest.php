<?php

namespace Yoti\Test\DocScan\Session\Create\Check;

use PHPUnit\Framework\Assert;
use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningConfig;
use Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningConfigBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningConfig
 */
class RequestedWatchlistScreeningConfigTest extends TestCase
{
    /**
     * @var string
     */
    private const SOME_UNKNOWN_CATEGORY = 'SOME_UNKNOWN_CATEGORY';

    /**
     * @test
     * @covers ::getCategories
     */
    public function builderShouldBuildWithoutAnySuppliedCategories()
    {
        $result = (new RequestedWatchlistScreeningConfigBuilder())->build();

        Assert::assertEmpty($result->getCategories());
    }

    /**
     * @test
     * @covers ::getCategories
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningConfigBuilder::withAdverseMediaCategory
     */
    public function builderShouldBuildWithAdverseMediaCategory()
    {
        $result = (new RequestedWatchlistScreeningConfigBuilder())
            ->withAdverseMediaCategory()
            ->build();

        Assert::assertCount(1, $result->getCategories());
        Assert::assertContains(Constants::ADVERSE_MEDIA, $result->getCategories());
    }

    /**
     * @test
     * @covers ::getCategories
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningConfigBuilder::withSanctionsCategory
     */
    public function builderShouldBuildWithSanctionsCategory()
    {
        $result = (new RequestedWatchlistScreeningConfigBuilder())
            ->withSanctionsCategory()
            ->build();

        Assert::assertCount(1, $result->getCategories());
        Assert::assertContains(Constants::SANCTIONS, $result->getCategories());
    }

    /**
     * @test
     * @covers ::getCategories
     */
    public function builderShouldAllowMultipleCategoriesToBeAdded()
    {
        $result = (new RequestedWatchlistScreeningConfigBuilder())
            ->withSanctionsCategory()
            ->withAdverseMediaCategory()
            ->build();

        Assert::assertCount(2, $result->getCategories());
        Assert::assertContains(Constants::SANCTIONS, $result->getCategories());
        Assert::assertContains(Constants::ADVERSE_MEDIA, $result->getCategories());
    }

    /**
     * @test
     * @covers ::getCategories
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningConfigBuilder::withAdverseMediaCategory
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningConfigBuilder::withSanctionsCategory
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningConfigBuilder::withCategory
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningConfigBuilder::build
     * @covers ::__construct
     */
    public function builderShouldNotAddCategoryMoreThanOnceEvenIfCalled()
    {
        $result = (new RequestedWatchlistScreeningConfigBuilder())
            ->withCategory(Constants::ADVERSE_MEDIA)
            ->withCategory(Constants::ADVERSE_MEDIA)
            ->withAdverseMediaCategory()
            ->withAdverseMediaCategory()
            ->build();

        Assert::assertCount(1, $result->getCategories());
        Assert::assertContains(Constants::ADVERSE_MEDIA, $result->getCategories());
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningConfigBuilder::build
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningConfigBuilder::withCategory
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningConfigBuilder::withSanctionsCategory
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedWatchlistScreeningConfigBuilder::withAdverseMediaCategory
     */
    public function builderShouldNotAllowNullCategory()
    {
        $this->expectException(\InvalidArgumentException::class);

        (new RequestedWatchlistScreeningConfigBuilder())
            ->withCategory('')
            ->build();
    }

    /**
     * @test
     * @covers ::getCategories
     */
    public function builderShouldAllowCategoryUnknownToTheSdk()
    {
        $result = (new RequestedWatchlistScreeningConfigBuilder())
            ->withCategory(self::SOME_UNKNOWN_CATEGORY)
            ->withAdverseMediaCategory()
            ->withSanctionsCategory()
            ->build();

        Assert::assertCount(3, $result->getCategories());
        Assert::assertContains(Constants::ADVERSE_MEDIA, $result->getCategories());
        Assert::assertContains(Constants::SANCTIONS, $result->getCategories());
        Assert::assertContains(self::SOME_UNKNOWN_CATEGORY, $result->getCategories());
    }

    /**
     * @test
     * @covers ::jsonSerialize
     */
    public function shouldJsonEncodeCorrectly()
    {
        $someCategories = [
            'SOME_NEW',
            'SOME_OLD'
        ];

        $result = new RequestedWatchlistScreeningConfig($someCategories);

        $expected = [
            'categories' => [
                'SOME_NEW',
                'SOME_OLD'
            ],
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }
}
