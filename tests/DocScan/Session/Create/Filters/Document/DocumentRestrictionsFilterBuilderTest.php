<?php

namespace Yoti\Test\DocScan\Session\Create\Check\Filters\Document;

use Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilterBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilterBuilder
 */
class DocumentRestrictionsFilterBuilderTest extends TestCase
{
    private const SOME_DOCUMENT_TYPE = 'some-document-type';
    private const SOME_COUNTRY_CODE = 'some-country-code';

    /**
     * @test
     *
     * @covers ::forWhitelist
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::jsonSerialize
     */
    public function shouldBuildDocumentRestrictionsFilterForWhitelist()
    {
        $filter = (new DocumentRestrictionsFilterBuilder())
            ->forWhitelist()
            ->withDocumentRestriction([], [])
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => 'DOCUMENT_RESTRICTIONS',
                    'inclusion' => 'WHITELIST',
                    'documents' => [ (object) [] ],
                ]
            ),
            json_encode($filter)
        );
    }

    /**
     * @test
     *
     * @covers ::forBlacklist
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::jsonSerialize
     */
    public function shouldBuildDocumentRestrictionsFilterForBlacklist()
    {
        $filter = (new DocumentRestrictionsFilterBuilder())
            ->forBlacklist()
            ->withDocumentRestriction([], [])
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => 'DOCUMENT_RESTRICTIONS',
                    'inclusion' => 'BLACKLIST',
                    'documents' => [ (object) [] ],
                ]
            ),
            json_encode($filter)
        );
    }

    /**
     * @test
     *
     * @covers ::withDocumentRestriction
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestriction::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestriction::jsonSerialize
     */
    public function shouldBuildDocumentRestrictionsFilterWithCountryAndDocument()
    {
        $filter = (new DocumentRestrictionsFilterBuilder())
            ->forWhitelist()
            ->withDocumentRestriction([self::SOME_COUNTRY_CODE], [self::SOME_DOCUMENT_TYPE])
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => 'DOCUMENT_RESTRICTIONS',
                    'inclusion' => 'WHITELIST',
                    'documents' => [
                        (object) [
                            'document_types' => [self::SOME_DOCUMENT_TYPE],
                            'country_codes' => [self::SOME_COUNTRY_CODE]
                        ]
                    ],
                ]
            ),
            json_encode($filter)
        );
    }

    /**
     * @test
     *
     * @covers ::withDocumentRestriction
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::jsonSerialize
     */
    public function shouldBuildDocumentRestrictionsFilterWithCountryOnly()
    {
        $filter = (new DocumentRestrictionsFilterBuilder())
            ->forWhitelist()
            ->withDocumentRestriction([self::SOME_COUNTRY_CODE], [])
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => 'DOCUMENT_RESTRICTIONS',
                    'inclusion' => 'WHITELIST',
                    'documents' => [
                        (object) [
                            'country_codes' => [self::SOME_COUNTRY_CODE]
                        ]
                    ],
                ]
            ),
            json_encode($filter)
        );
    }


    /**
     * @test
     *
     * @covers ::withDocumentRestriction
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::jsonSerialize
     */
    public function shouldBuildDocumentRestrictionsFilterWithDocumentOnly()
    {
        $filter = (new DocumentRestrictionsFilterBuilder())
            ->forWhitelist()
            ->withDocumentRestriction([], [self::SOME_DOCUMENT_TYPE])
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => 'DOCUMENT_RESTRICTIONS',
                    'inclusion' => 'WHITELIST',
                    'documents' => [
                        (object) [
                            'document_types' => [self::SOME_DOCUMENT_TYPE],
                        ]
                    ],
                ]
            ),
            json_encode($filter)
        );
    }

    /**
     * @test
     *
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::__construct
     */
    public function shouldThrowExceptionWithoutInclusion()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('inclusion must be a string');

        (new DocumentRestrictionsFilterBuilder())->build();
    }

    /**
     * @test
     *
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::__construct
     */
    public function shouldThrowExceptionWithoutDocumentRestriction()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('documents cannot be empty');

        (new DocumentRestrictionsFilterBuilder())
            ->forWhitelist()
            ->build();
    }
}
