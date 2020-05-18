<?php

namespace Yoti\Test\DocScan\Session\Create\Check\Filters\Orthogonal;

use Yoti\DocScan\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilterBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilterBuilder
 */
class OrthogonalRestrictionsFilterBuilderTest extends TestCase
{
    private const SOME_DOCUMENT_TYPE = 'some-document-type';
    private const SOME_COUNTRY_CODE = 'some-country-code';

    /**
     * @test
     *
     * @covers ::withWhitelistedCountries
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Filters\Orthogonal\CountryRestriction::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Orthogonal\CountryRestriction::jsonSerialize
     */
    public function shouldBuildOrthogonalRestrictionsFilterWithWhitelistedCountries()
    {
        $filter = (new OrthogonalRestrictionsFilterBuilder())
            ->withWhitelistedCountries([self::SOME_COUNTRY_CODE])
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => 'ORTHOGONAL_RESTRICTIONS',
                    'country_restriction' => (object) [
                        'inclusion' => 'WHITELIST',
                        'country_codes' => [self::SOME_COUNTRY_CODE],
                    ],
                ]
            ),
            json_encode($filter)
        );
    }

    /**
     * @test
     *
     * @covers ::withBlacklistedCountries
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Filters\Orthogonal\CountryRestriction::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Orthogonal\CountryRestriction::jsonSerialize
     */
    public function shouldBuildOrthogonalRestrictionsFilterWithBlacklistedCountries()
    {
        $filter = (new OrthogonalRestrictionsFilterBuilder())
            ->withBlacklistedCountries([self::SOME_COUNTRY_CODE])
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => 'ORTHOGONAL_RESTRICTIONS',
                    'country_restriction' => (object) [
                        'inclusion' => 'BLACKLIST',
                        'country_codes' => [self::SOME_COUNTRY_CODE],
                    ],
                ]
            ),
            json_encode($filter)
        );
    }

    /**
     * @test
     *
     * @covers ::withWhitelistedDocumentTypes
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Filters\Orthogonal\TypeRestriction::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Orthogonal\TypeRestriction::jsonSerialize
     */
    public function shouldBuildOrthogonalRestrictionsFilterWithWhitelistedDocumentTypes()
    {
        $filter = (new OrthogonalRestrictionsFilterBuilder())
            ->withWhitelistedDocumentTypes([self::SOME_DOCUMENT_TYPE])
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => 'ORTHOGONAL_RESTRICTIONS',
                    'type_restriction' => (object) [
                        'inclusion' => 'WHITELIST',
                        'document_types' => [self::SOME_DOCUMENT_TYPE],
                    ],
                ]
            ),
            json_encode($filter)
        );
    }


    /**
     * @test
     *
     * @covers ::withBlacklistedDocumentTypes
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Filters\Orthogonal\TypeRestriction::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Orthogonal\TypeRestriction::jsonSerialize
     */
    public function shouldBuildOrthogonalRestrictionsFilterWithBlacklistedDocumentTypes()
    {
        $filter = (new OrthogonalRestrictionsFilterBuilder())
            ->withBlacklistedDocumentTypes([self::SOME_DOCUMENT_TYPE])
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => 'ORTHOGONAL_RESTRICTIONS',
                    'type_restriction' => (object) [
                        'inclusion' => 'BLACKLIST',
                        'document_types' => [self::SOME_DOCUMENT_TYPE],
                    ],
                ]
            ),
            json_encode($filter)
        );
    }

    /**
     * @test
     *
     * @covers ::withWhitelistedCountries
     * @covers ::withWhitelistedDocumentTypes
     * @covers ::build
     */
    public function shouldBuildOrthogonalRestrictionsFilterWithWhitelistedDocumentTypesAndCountries()
    {
        $filter = (new OrthogonalRestrictionsFilterBuilder())
            ->withWhitelistedDocumentTypes([self::SOME_DOCUMENT_TYPE])
            ->withWhitelistedCountries([self::SOME_COUNTRY_CODE])
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => 'ORTHOGONAL_RESTRICTIONS',
                    'country_restriction' => (object) [
                        'inclusion' => 'WHITELIST',
                        'country_codes' => [self::SOME_COUNTRY_CODE],
                    ],
                    'type_restriction' => (object) [
                        'inclusion' => 'WHITELIST',
                        'document_types' => [self::SOME_DOCUMENT_TYPE],
                    ],
                ]
            ),
            json_encode($filter)
        );
    }

    /**
     * @test
     *
     * @covers ::withBlacklistedCountries
     * @covers ::withBlacklistedDocumentTypes
     * @covers ::build
     */
    public function shouldBuildOrthogonalRestrictionsFilterWithBlacklistedDocumentTypesAndCountries()
    {
        $filter = (new OrthogonalRestrictionsFilterBuilder())
            ->withBlacklistedDocumentTypes([self::SOME_DOCUMENT_TYPE])
            ->withBlacklistedCountries([self::SOME_COUNTRY_CODE])
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => 'ORTHOGONAL_RESTRICTIONS',
                    'country_restriction' => (object) [
                        'inclusion' => 'BLACKLIST',
                        'country_codes' => [self::SOME_COUNTRY_CODE],
                    ],
                    'type_restriction' => (object) [
                        'inclusion' => 'BLACKLIST',
                        'document_types' => [self::SOME_DOCUMENT_TYPE],
                    ],
                ]
            ),
            json_encode($filter)
        );
    }
}
