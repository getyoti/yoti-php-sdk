<?php

namespace Yoti\Test\DocScan\Session\Create\Check;

use Yoti\DocScan\Session\Create\Check\IssuingAuthoritySubCheckBuilder;
use Yoti\DocScan\Session\Create\Filters\DocumentFilter;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Check\IssuingAuthoritySubCheck
 */
class IssuingAuthoritySubCheckTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     * @covers ::isRequested
     * @covers ::getFilter
     * @covers \Yoti\DocScan\Session\Create\Check\IssuingAuthoritySubCheckBuilder::withDocumentFilter
     * @covers \Yoti\DocScan\Session\Create\Check\IssuingAuthoritySubCheckBuilder::withRequested
     * @covers \Yoti\DocScan\Session\Create\Check\IssuingAuthoritySubCheckBuilder::build
     */
    public function withRequestedAndDocumentFilterShouldSetTheValueCorrectly()
    {
        $documentFilterMock = $this->getMockForAbstractClass(DocumentFilter::class, ['type' => 'some']);

        $result = (new IssuingAuthoritySubCheckBuilder())
            ->withRequested(true)
            ->withDocumentFilter($documentFilterMock)
            ->build();

        $this->assertTrue($result->isRequested());
        $this->assertInstanceOf(DocumentFilter::class, $result->getFilter());
        $this->assertEquals($documentFilterMock, $result->getFilter());
    }
}
