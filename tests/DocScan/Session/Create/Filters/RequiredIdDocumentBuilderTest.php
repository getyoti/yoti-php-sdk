<?php

declare(strict_types=1);

namespace Yoti\Test\IDV\Session\Create\Filters;

use Yoti\IDV\Session\Create\Filters\DocumentFilter;
use Yoti\IDV\Session\Create\Filters\RequiredDocument;
use Yoti\IDV\Session\Create\Filters\RequiredIdDocument;
use Yoti\IDV\Session\Create\Filters\RequiredIdDocumentBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Create\Filters\RequiredIdDocumentBuilder
 */
class RequiredIdDocumentBuilderTest extends TestCase
{
    /**
     * @test
     *
     * @covers ::build
     * @covers \Yoti\IDV\Session\Create\Filters\RequiredIdDocument::__construct
     * @covers \Yoti\IDV\Session\Create\Filters\RequiredIdDocument::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\Filters\RequiredDocument::__construct
     * @covers \Yoti\IDV\Session\Create\Filters\RequiredDocument::jsonSerialize
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
     * @covers \Yoti\IDV\Session\Create\Filters\RequiredIdDocument::__construct
     * @covers \Yoti\IDV\Session\Create\Filters\RequiredIdDocument::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\Filters\RequiredDocument::__construct
     * @covers \Yoti\IDV\Session\Create\Filters\RequiredDocument::jsonSerialize
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
