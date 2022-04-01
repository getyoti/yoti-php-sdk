<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Retrieve\Contracts\CaMatchingStrategyResponse;
use Yoti\DocScan\Session\Retrieve\Contracts\CaSourcesResponse;
use Yoti\DocScan\Session\Retrieve\Contracts\WatchlistSearchConfigResponse;
use Yoti\DocScan\Session\Retrieve\MediaResponse;
use Yoti\DocScan\Session\Retrieve\RawResultsResponse;
use Yoti\DocScan\Session\Retrieve\ReportResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\ReportResponse
 */
class ReportResponseTest extends TestCase
{
    private const TOTAL_HITS = 7;
    private const SOME_COUNTRY_CODE = ['GBR', 'USA'];
    private const SOME_TYPES = [ "someString", "someOtherString" ];
    private const SOME_API_KEY = 'SOME_API_KEY';
    private const SOME_CLIENT_REF = 'SOME_CLIENT_REF';
    private const SOME_SEARCH_PROFILE = 'SOME_SEARCH_PROFILE';
    private const SOME_FUZZINESS = 0.7;
    private const SOME_CATEGORIES = [ "someString", "someOtherString" ];

    /**
     * @test
     * @covers ::__construct
     * @covers ::getRecommendation
     * @covers ::getBreakdown
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'recommendation' => [],
            'breakdown' => [
                [ 'someKey' => 'someValue' ],
                [ 'someOtherKey' => 'someOtherValue' ],
            ],
        ];

        $result = new ReportResponse($input);

        $this->assertNotNull($result->getRecommendation());
        $this->assertCount(2, $result->getBreakdown());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenMissingValues()
    {
        $result = new ReportResponse([]);

        $this->assertNull($result->getRecommendation());
        $this->assertCount(0, $result->getBreakdown());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getWatchlistSummary
     * @covers \Yoti\DocScan\Session\Retrieve\WatchlistSummaryResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\WatchlistSummaryResponse::setSearchConfig
     * @covers \Yoti\DocScan\Session\Retrieve\WatchlistSummaryResponse::getTotalHits
     * @covers \Yoti\DocScan\Session\Retrieve\WatchlistSummaryResponse::getRawResults
     * @covers \Yoti\DocScan\Session\Retrieve\WatchlistSummaryResponse::getSearchConfig
     * @covers \Yoti\DocScan\Session\Retrieve\WatchlistSummaryResponse::getAssociatedCountryCodes
     * @covers \Yoti\DocScan\Session\Retrieve\WatchlistAdvancedCaSearchConfigResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\WatchlistAdvancedCaSearchConfigResponse::getSources
     * @covers \Yoti\DocScan\Session\Retrieve\WatchlistAdvancedCaSearchConfigResponse::getMatchingStrategy
     * @covers \Yoti\DocScan\Session\Retrieve\WatchlistAdvancedCaSearchConfigResponse::getType
     * @covers \Yoti\DocScan\Session\Retrieve\WatchlistAdvancedCaSearchConfigResponse::isShareUrl
     * @covers \Yoti\DocScan\Session\Retrieve\WatchlistAdvancedCaSearchConfigResponse::isRemoveDeceased
     * @covers \Yoti\DocScan\Session\Retrieve\WatchlistAdvancedCaSearchConfigResponse::setMatchingStrategy
     * @covers \Yoti\DocScan\Session\Retrieve\WatchlistAdvancedCaSearchConfigResponse::setSources
     * @covers \Yoti\DocScan\Session\Retrieve\RawResultsResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\RawResultsResponse::getMedia
     * @covers \Yoti\DocScan\Session\Retrieve\Sources\TypeListSourcesResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\Sources\TypeListSourcesResponse::getTypes
     * @covers \Yoti\DocScan\Session\Retrieve\Sources\TypeListSourcesResponse::getType
     * @covers \Yoti\DocScan\Session\Retrieve\Sources\SearchProfileSourcesResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\Sources\SearchProfileSourcesResponse::getSearchProfile
     * @covers \Yoti\DocScan\Session\Retrieve\Sources\SearchProfileSourcesResponse::getType
     * @covers \Yoti\DocScan\Session\Retrieve\Contracts\CaSourcesResponse::getType
     * @covers \Yoti\DocScan\Session\Retrieve\Matching\FuzzyMatchingStrategyResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\Matching\FuzzyMatchingStrategyResponse::getFuzziness
     * @covers \Yoti\DocScan\Session\Retrieve\Matching\FuzzyMatchingStrategyResponse::getType
     * @covers \Yoti\DocScan\Session\Retrieve\Matching\ExactMatchingStrategyResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\Matching\ExactMatchingStrategyResponse::isExactMatch
     * @covers \Yoti\DocScan\Session\Retrieve\Matching\ExactMatchingStrategyResponse::getType
     * @covers \Yoti\DocScan\Session\Retrieve\Contracts\CaSourcesResponse::getType
     * @covers \Yoti\DocScan\Session\Retrieve\Contracts\CaMatchingStrategyResponse::getType
     * @covers \Yoti\DocScan\Session\Retrieve\CustomAccountWatchlistCaSearchConfigResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\CustomAccountWatchlistCaSearchConfigResponse::getApiKey
     * @covers \Yoti\DocScan\Session\Retrieve\CustomAccountWatchlistCaSearchConfigResponse::getMonitoring
     * @covers \Yoti\DocScan\Session\Retrieve\CustomAccountWatchlistCaSearchConfigResponse::getTags
     * @covers \Yoti\DocScan\Session\Retrieve\CustomAccountWatchlistCaSearchConfigResponse::getClientRef
     */
    public function shouldBuildWithWatchlistSummaryResponse()
    {
        $tags = [
            'some' => 'exact'
        ];
        $someTags = json_encode($tags);

        $input = [
            'watchlist_summary' => [
                'total_hits' => self::TOTAL_HITS,
                'raw_results' => [
                    'media' => [
                        'id' => 'SOME_ID',
                        'type' => 'SOME_TYPE',
                        'created' => "2021-02-16T17:02:53.519Z",
                        'last_updated' => "2021-02-16T17:02:53.519Z"
                    ]
                ],
                'associated_country_codes' => self::SOME_COUNTRY_CODE,
                'search_config' => [
                    'type' => Constants::WITH_YOTI_ACCOUNT,
                    'remove_deceased' => true,
                    'share_url' => true,
                    'sources' => [
                        'type' => Constants::TYPE_LIST,
                        'types' => self::SOME_TYPES
                    ],
                    'matching_strategy' => [
                        'type' => Constants::EXACT,
                        'exact_match' => true
                    ]
                ]
            ]
        ];

        $result = new ReportResponse($input);

        $this->assertNotNull($result->getWatchlistSummary());
        $this->assertEquals(self::TOTAL_HITS, $result->getWatchlistSummary()->getTotalHits());
        $this->assertEquals(
            self::SOME_COUNTRY_CODE,
            $result->getWatchlistSummary()->getAssociatedCountryCodes()
        );

        $this->assertInstanceOf(RawResultsResponse::class, $result->getWatchlistSummary()->getRawResults());
        $this->assertInstanceOf(
            WatchlistSearchConfigResponse::class,
            $result->getWatchlistSummary()->getSearchConfig()
        );

        $this->assertTrue($result->getWatchlistSummary()->getSearchConfig()->isRemoveDeceased());
        $this->assertTrue($result->getWatchlistSummary()->getSearchConfig()->isShareUrl());
        $this->assertInstanceOf(
            CaMatchingStrategyResponse::class,
            $result->getWatchlistSummary()->getSearchConfig()->getMatchingStrategy()
        );
        $this->assertInstanceOf(
            CaSourcesResponse::class,
            $result->getWatchlistSummary()->getSearchConfig()->getSources()
        );
        $this->assertEquals(
            Constants::WITH_YOTI_ACCOUNT,
            $result->getWatchlistSummary()->getSearchConfig()->getType()
        );

        $this->assertEquals(
            self::SOME_TYPES,
            $result->getWatchlistSummary()->getSearchConfig()->getSources()->getTypes()
        );

        $this->assertTrue(
            $result->getWatchlistSummary()->getSearchConfig()->getMatchingStrategy()->isExactMatch()
        );

        $input2 = [
            'watchlist_summary' => [
                'total_hits' => self::TOTAL_HITS,
                'raw_results' => [
                    'media' => [
                        'id' => 'SOME_ID',
                        'type' => 'SOME_TYPE',
                        'created' => "2021-02-16T17:02:53.519Z",
                        'last_updated' => "2021-02-16T17:02:53.519Z"
                    ]
                ],
                'associated_country_codes' => self::SOME_COUNTRY_CODE,
                'search_config' => [
                    'type' => Constants::WITH_CUSTOM_ACCOUNT,
                    'remove_deceased' => true,
                    'share_url' => true,
                    'api_key' => self::SOME_API_KEY,
                    'monitoring' => false,
                    'client_ref' => self::SOME_CLIENT_REF,
                    'tags' => $someTags,
                    'sources' => [
                        'type' => Constants::PROFILE,
                        'search_profile' => self::SOME_SEARCH_PROFILE
                    ],
                    'matching_strategy' => [
                        'type' => Constants::FUZZY,
                        'fuzziness' => self::SOME_FUZZINESS
                    ]
                ]
            ]
        ];

        $result2 = new ReportResponse($input2);

        $this->assertEquals(
            self::SOME_SEARCH_PROFILE,
            $result2->getWatchlistSummary()->getSearchConfig()->getSources()->getSearchProfile()
        );

        $this->assertInstanceOf(
            MediaResponse::class,
            $result2->getWatchlistSummary()->getRawResults()->getMedia()
        );

        $this->assertEquals(
            self::SOME_FUZZINESS,
            $result2->getWatchlistSummary()->getSearchConfig()->getMatchingStrategy()->getFuzziness()
        );

        $this->assertEquals(
            self::SOME_API_KEY,
            $result2->getWatchlistSummary()->getSearchConfig()->getApiKey()
        );

        $this->assertFalse(
            $result2->getWatchlistSummary()->getSearchConfig()->getMonitoring()
        );

        $this->assertEquals(
            $tags,
            $result2->getWatchlistSummary()->getSearchConfig()->getTags()
        );

        $this->assertEquals(
            self::SOME_CLIENT_REF,
            $result2->getWatchlistSummary()->getSearchConfig()->getCLientRef()
        );
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Retrieve\WatchlistScreeningSearchConfigResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\WatchlistScreeningSearchConfigResponse::getCategories
     * @covers \Yoti\DocScan\Session\Retrieve\WatchlistSummaryResponse::setSearchConfig
     */
    public function shouldBuildWithWatchlistScreening()
    {
        $input = [
            'watchlist_summary' => [
                'total_hits' => self::TOTAL_HITS,
                'search_config' => [
                    'categories' => self::SOME_CATEGORIES
                ]
            ]
        ];
        $result = new ReportResponse($input);

        $this->assertEquals(
            self::SOME_CATEGORIES,
            $result->getWatchlistSummary()->getSearchConfig()->getCategories()
        );
    }
}
