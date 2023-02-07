<?php

namespace Yoti\Test\IDV\Session\Create\Check\Filters\Orthogonal;

use Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilterBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilterBuilder
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
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::__construct
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\CountryRestriction::__construct
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\CountryRestriction::jsonSerialize
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
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::__construct
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\CountryRestriction::__construct
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\CountryRestriction::jsonSerialize
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
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::__construct
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\TypeRestriction::__construct
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\TypeRestriction::jsonSerialize
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
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::__construct
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\TypeRestriction::__construct
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\TypeRestriction::jsonSerialize
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

    /**
     * @test
     *
     * @covers ::withAllowNonLatinDocuments
     * @covers ::build
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::__construct
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::jsonSerialize
     */
    public function shouldBuildWithAllowNonLatinDocuments()
    {
        $filter = (new OrthogonalRestrictionsFilterBuilder())
            ->withBlacklistedDocumentTypes([self::SOME_DOCUMENT_TYPE])
            ->withBlacklistedCountries([self::SOME_COUNTRY_CODE])
            ->withAllowNonLatinDocuments()
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => 'ORTHOGONAL_RESTRICTIONS',
                    'allow_non_latin_documents' => true,
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

        $this->assertTrue($filter->isAllowNonLatinDocuments());
    }

    /**
     * @test
     *
     * @covers ::withAllowNonLatinDocuments
     * @covers ::build
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::__construct
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::isAllowNonLatinDocuments
     */
    public function shouldBuildAndAllowNonLatinDocumentsEqualsNull()
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

        $this->assertNull($filter->isAllowNonLatinDocuments());
    }

    /**
     * @test
     *
     * @covers ::withAllowExpiredDocuments
     * @covers ::build
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::__construct
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::isAllowExpiredDocuments
     */
    public function shouldBuildWithAllowExpiredDocuments()
    {
        $filter = (new OrthogonalRestrictionsFilterBuilder())
            ->withBlacklistedDocumentTypes([self::SOME_DOCUMENT_TYPE])
            ->withBlacklistedCountries([self::SOME_COUNTRY_CODE])
            ->withAllowExpiredDocuments()
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => 'ORTHOGONAL_RESTRICTIONS',
                    'allow_expired_documents' => true,
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

        $this->assertTrue($filter->isAllowExpiredDocuments());
    }

    /**
     * @test
     *
     * @covers ::withDenyExpiredDocuments
     * @covers ::build
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::__construct
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::isAllowExpiredDocuments
     */
    public function shouldBuildWithDenyExpiredDocuments()
    {
        $filter = (new OrthogonalRestrictionsFilterBuilder())
            ->withBlacklistedDocumentTypes([self::SOME_DOCUMENT_TYPE])
            ->withBlacklistedCountries([self::SOME_COUNTRY_CODE])
            ->withDenyExpiredDocuments()
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => 'ORTHOGONAL_RESTRICTIONS',
                    'allow_expired_documents' => false,
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

        $this->assertFalse($filter->isAllowExpiredDocuments());
    }

    /**
     * @test
     *
     * @covers ::build
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::__construct
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\Filters\Orthogonal\OrthogonalRestrictionsFilter::isAllowExpiredDocuments
     */
    public function shouldBuildAndAllowExpiredDocumentsEqualsNull()
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

        $this->assertNull($filter->isAllowExpiredDocuments());
    }
}
