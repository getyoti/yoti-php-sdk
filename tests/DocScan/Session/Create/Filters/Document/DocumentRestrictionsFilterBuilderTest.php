<?php

namespace Yoti\Test\DocScan\Session\Create\Check\Filters\Document;

use Yoti\DocScan\Session\Create\Filters\Document\DocumentRestriction;
use Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilterBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilterBuilder
 */
class DocumentRestrictionsFilterBuilderTest extends TestCase
{
    /**
     * @var DocumentRestriction
     */
    private $documentRestrictionMock;

    public function setup(): void
    {
        $this->documentRestrictionMock = $this->createMock(DocumentRestriction::class);
        $this->documentRestrictionMock
            ->method('jsonSerialize')
            ->willReturn((object)['some' => 'restriction']);
    }

    /**
     * @test
     *
     * @covers ::forWhitelist
     * @covers ::withDocumentRestriction
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::jsonSerialize
     */
    public function shouldBuildDocumentRestrictionsFilterForWhitelist()
    {
        $filter = (new DocumentRestrictionsFilterBuilder())
            ->forWhitelist()
            ->withDocumentRestriction($this->documentRestrictionMock)
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => 'DOCUMENT_RESTRICTIONS',
                    'inclusion' => 'WHITELIST',
                    'documents' => [ $this->documentRestrictionMock ],
                ]
            ),
            json_encode($filter)
        );
    }

    /**
     * @test
     *
     * @covers ::forBlacklist
     * @covers ::withDocumentRestriction
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::jsonSerialize
     */
    public function shouldBuildDocumentRestrictionsFilterForBlacklist()
    {
        $filter = (new DocumentRestrictionsFilterBuilder())
            ->forBlacklist()
            ->withDocumentRestriction($this->documentRestrictionMock)
            ->withAllowNonLatinDocuments()
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => 'DOCUMENT_RESTRICTIONS',
                    'allow_non_latin_documents' => true,
                    'inclusion' => 'BLACKLIST',
                    'documents' => [ $this->documentRestrictionMock ],
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

    /**
     * @test
     *
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::isAllowNonLatinDocuments
     * @covers ::forBlacklist
     * @covers ::withAllowNonLatinDocuments
     * @covers ::withDocumentRestriction
     * @covers ::build
     */
    public function shouldBuildWithAllowNonLatinDocuments(): void
    {
        $filter = (new DocumentRestrictionsFilterBuilder())
            ->forBlacklist()
            ->withDocumentRestriction($this->documentRestrictionMock)
            ->withAllowNonLatinDocuments()
            ->build();

        $this->assertTrue($filter->isAllowNonLatinDocuments());
    }

    /**
     * @test
     *
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::isAllowNonLatinDocuments
     * @covers ::forBlacklist
     * @covers ::withDocumentRestriction
     * @covers ::build
     */
    public function shouldBuildAndAllowNonLatinDocumentsEqualsNull(): void
    {
        $filter = (new DocumentRestrictionsFilterBuilder())
            ->forBlacklist()
            ->withDocumentRestriction($this->documentRestrictionMock)
            ->build();

        $this->assertNull($filter->isAllowNonLatinDocuments());
    }

    /**
     * @test
     *
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::isAllowExpiredDocuments
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::jsonSerialize
     * @covers ::forBlacklist
     * @covers ::withAllowExpiredDocuments
     * @covers ::withDocumentRestriction
     */
    public function shouldBuildWithAllowExpiredDocuments(): void
    {
        $filter = (new DocumentRestrictionsFilterBuilder())
            ->forBlacklist()
            ->withDocumentRestriction($this->documentRestrictionMock)
            ->withAllowExpiredDocuments()
            ->build();

        $this->assertTrue($filter->isAllowExpiredDocuments());
    }

    /**
     * @test
     *
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::isAllowExpiredDocuments
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::jsonSerialize
     * @covers ::forBlacklist
     * @covers ::withDenyExpiredDocuments
     * @covers ::withDocumentRestriction
     */
    public function shouldBuildWithDenyExpiredDocuments(): void
    {
        $filter = (new DocumentRestrictionsFilterBuilder())
            ->forBlacklist()
            ->withDocumentRestriction($this->documentRestrictionMock)
            ->withDenyExpiredDocuments()
            ->build();

        $this->assertFalse($filter->isAllowExpiredDocuments());
    }

    /**
     * @test
     *
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::isAllowExpiredDocuments
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::jsonSerialize
     * @covers ::forBlacklist
     * @covers ::withDocumentRestriction
     */
    public function shouldBuildAndAllowExpiredDocumentsEqualsNull(): void
    {
        $filter = (new DocumentRestrictionsFilterBuilder())
            ->forBlacklist()
            ->withDocumentRestriction($this->documentRestrictionMock)
            ->build();

        $this->assertNull($filter->isAllowExpiredDocuments());
    }

    /**
     * @test
     *
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\DocumentFilter::isAllowDigitalIds
     * @covers \Yoti\DocScan\Session\Create\Filters\DocumentFilter::jsonSerialize
     * @covers ::forBlacklist
     * @covers ::withAllowDigitalIds
     * @covers ::withDocumentRestriction
     */
    public function shouldBuildWithAllowDigitalIds(): void
    {
        $filter = (new DocumentRestrictionsFilterBuilder())
            ->forBlacklist()
            ->withDocumentRestriction($this->documentRestrictionMock)
            ->withAllowDigitalIds()
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => 'DOCUMENT_RESTRICTIONS',
                    'allow_digital_ids' => true,
                    'inclusion' => 'BLACKLIST',
                    'documents' => [ $this->documentRestrictionMock ],
                ]
            ),
            json_encode($filter)
        );

        $this->assertTrue($filter->isAllowDigitalIds());
    }

    /**
     * @test
     *
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\Document\DocumentRestrictionsFilter::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\DocumentFilter::getAllowedProviders
     * @covers \Yoti\DocScan\Session\Create\Filters\DocumentFilter::jsonSerialize
     * @covers ::forBlacklist
     * @covers ::withAllowedProviders
     * @covers ::withDocumentRestriction
     */
    public function shouldBuildWithAllowedProviders(): void
    {
        $filter = (new DocumentRestrictionsFilterBuilder())
            ->forBlacklist()
            ->withDocumentRestriction($this->documentRestrictionMock)
            ->withAllowedProviders(['DIGILOCKER', 'EPHIL_ID_QR'])
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => 'DOCUMENT_RESTRICTIONS',
                    'allowed_providers' => [
                        (object) ['name' => 'DIGILOCKER'],
                        (object) ['name' => 'EPHIL_ID_QR'],
                    ],
                    'inclusion' => 'BLACKLIST',
                    'documents' => [ $this->documentRestrictionMock ],
                ]
            ),
            json_encode($filter)
        );

        $this->assertCount(2, $filter->getAllowedProviders());
    }
}
