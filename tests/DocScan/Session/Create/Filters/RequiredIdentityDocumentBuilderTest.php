<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Create\Filters;

use Yoti\DocScan\Session\Create\Filters\DocumentFilter;
use Yoti\DocScan\Session\Create\Filters\RequiredDocument;
use Yoti\DocScan\Session\Create\Filters\RequiredIdentityDocument;
use Yoti\DocScan\Session\Create\Filters\RequiredIdentityDocumentBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Filters\RequiredIdentityDocumentBuilder
 */
class RequiredIdentityDocumentBuilderTest extends TestCase
{
    /**
     * @test
     *
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredIdentityDocument::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredIdentityDocument::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredDocument::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredDocument::jsonSerialize
     */
    public function shouldBuildRequiredIdentityDocumentWithoutFilter()
    {
        $requiredDocument = (new RequiredIdentityDocumentBuilder())->build();

        $this->assertInstanceOf(RequiredDocument::class, $requiredDocument);
        $this->assertInstanceOf(RequiredIdentityDocument::class, $requiredDocument);

        $this->assertJsonStringEqualsJsonString(
            json_encode((object)[
                'type' => 'ID_DOCUMENT',
            ]),
            json_encode($requiredDocument)
        );
    }

    /**
     * @test
     *
     * @covers ::build
     * @covers ::withFilter
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredIdentityDocument::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredIdentityDocument::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredDocument::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredDocument::jsonSerialize
     */
    public function shouldBuildRequiredIdentityDocumentWithFilter()
    {
        $filterMock = $this->createMock(DocumentFilter::class);
        $filterMock->method('jsonSerialize')->willReturn((object) ['some' => 'filter']);

        $requiredDocument = (new RequiredIdentityDocumentBuilder())
            ->withFilter($filterMock)
            ->build();

        $this->assertInstanceOf(RequiredDocument::class, $requiredDocument);
        $this->assertInstanceOf(RequiredIdentityDocument::class, $requiredDocument);

        $this->assertJsonStringEqualsJsonString(
            json_encode((object)[
                'type' => 'ID_DOCUMENT',
                'filter' => $filterMock,
            ]),
            json_encode($requiredDocument)
        );
    }
}
