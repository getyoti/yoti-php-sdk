<?php

namespace Yoti\Test\DocScan\Session\Create\Check\Filters\Document;

use Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionBuilder
 */
class DocumentRestrictionBuilderTest extends TestCase
{
    private const SOME_DOCUMENT_TYPE = 'some-document-type';
    private const SOME_OTHER_DOCUMENT_TYPE = 'some-other-document-type';
    private const SOME_COUNTRY_CODE = 'some-country-code';
    private const SOME_OTHER_COUNTRY_CODE = 'some-other-country-code';

    /**
     * @test
     *
     * @covers ::withDocumentTypes
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestriction::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestriction::jsonSerialize
     */
    public function shouldBuildDocumentRestrictionWithDocumentTypes()
    {
        $filter = (new DocumentRestrictionBuilder())
            ->withDocumentTypes([
                self::SOME_DOCUMENT_TYPE,
                self::SOME_OTHER_DOCUMENT_TYPE,
            ])
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'document_types' => [
                        self::SOME_DOCUMENT_TYPE,
                        self::SOME_OTHER_DOCUMENT_TYPE,
                    ],
                ]
            ),
            json_encode($filter)
        );
    }

    /**
     * @test
     *
     * @covers ::withCountries
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestriction::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestriction::jsonSerialize
     */
    public function shouldBuildDocumentRestrictionWithCountries()
    {
        $filter = (new DocumentRestrictionBuilder())
            ->withCountries([
                self::SOME_COUNTRY_CODE,
                self::SOME_OTHER_COUNTRY_CODE,
            ])
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'country_codes' => [
                        self::SOME_COUNTRY_CODE,
                        self::SOME_OTHER_COUNTRY_CODE,
                    ],
                ]
            ),
            json_encode($filter)
        );
    }

    /**
     * @test
     *
     * @covers ::withDocumentTypes
     * @covers ::withCountries
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestriction::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestriction::jsonSerialize
     */
    public function shouldBuildDocumentRestrictionWithCountriesAndDocumentTypes()
    {
        $filter = (new DocumentRestrictionBuilder())
            ->withCountries([
                self::SOME_COUNTRY_CODE,
                self::SOME_OTHER_COUNTRY_CODE,
            ])
            ->withDocumentTypes([
                self::SOME_DOCUMENT_TYPE,
                self::SOME_OTHER_DOCUMENT_TYPE,
            ])
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'country_codes' => [
                        self::SOME_COUNTRY_CODE,
                        self::SOME_OTHER_COUNTRY_CODE,
                    ],
                    'document_types' => [
                        self::SOME_DOCUMENT_TYPE,
                        self::SOME_OTHER_DOCUMENT_TYPE,
                    ],
                ]
            ),
            json_encode($filter)
        );
    }

    /**
     * @test
     *
     * @covers ::withCountries
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestriction::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestriction::jsonSerialize
     */
    public function shouldBuildDocumentRestrictionWithEmptyCountryList()
    {
        $filter = (new DocumentRestrictionBuilder())
            ->withCountries([])
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'country_codes' => [],
                ]
            ),
            json_encode($filter)
        );
    }

    /**
     * @test
     *
     * @covers ::withDocumentTypes
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestriction::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestriction::jsonSerialize
     */
    public function shouldBuildDocumentRestrictionWithEmptyDocumentTypeList()
    {
        $filter = (new DocumentRestrictionBuilder())
            ->withDocumentTypes([])
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'document_types' => [],
                ]
            ),
            json_encode($filter)
        );
    }
}
