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
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => 'DOCUMENT_RESTRICTIONS',
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
}
