<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Create\Filters;

use Yoti\DocScan\Session\Create\Filters\DocumentFilter;
use Yoti\DocScan\Session\Create\Filters\RequiredDocument;
use Yoti\DocScan\Session\Create\Filters\RequiredIdDocument;
use Yoti\DocScan\Session\Create\Filters\RequiredIdDocumentBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Filters\RequiredIdDocumentBuilder
 */
class RequiredIdDocumentBuilderTest extends TestCase
{
    /**
     * @test
     *
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredIdDocument::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredIdDocument::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredDocument::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredDocument::jsonSerialize
     */
    public function shouldBuildRequiredIdDocumentWithoutFilter()
    {
        $requiredDocument = (new RequiredIdDocumentBuilder())->build();

        $this->assertInstanceOf(RequiredDocument::class, $requiredDocument);
        $this->assertInstanceOf(RequiredIdDocument::class, $requiredDocument);

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
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredIdDocument::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredIdDocument::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredDocument::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredDocument::jsonSerialize
     */
    public function shouldBuildRequiredIdDocumentWithFilter()
    {
        $filterMock = $this->createMock(DocumentFilter::class);
        $filterMock->method('jsonSerialize')->willReturn((object) ['some' => 'filter']);

        $requiredDocument = (new RequiredIdDocumentBuilder())
            ->withFilter($filterMock)
            ->build();

        $this->assertInstanceOf(RequiredDocument::class, $requiredDocument);
        $this->assertInstanceOf(RequiredIdDocument::class, $requiredDocument);

        $this->assertJsonStringEqualsJsonString(
            json_encode((object)[
                'type' => 'ID_DOCUMENT',
                'filter' => $filterMock,
            ]),
            json_encode($requiredDocument)
        );
    }
}
