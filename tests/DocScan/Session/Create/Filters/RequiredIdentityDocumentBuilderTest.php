<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Create\Filters;

use Yoti\DocScan\Session\Create\Filters\RequiredDocument;
use Yoti\DocScan\Session\Create\Filters\RequiredDocumentFilter;
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
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredDocumentBuilder::withFilter
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredDocument::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredDocument::jsonSerialize
     */
    public function shouldBuildRequiredIdentityDocument()
    {
        $filterMock = $this->createMock(RequiredDocumentFilter::class);
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
