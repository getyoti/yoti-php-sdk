<?php

namespace Yoti\Test\DocScan\Session\Instructions\Document;

use Yoti\DocScan\Session\Instructions\Document\SelectedIdDocumentBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Instructions\Document\SelectedIdDocument
 */
class SelectedIdDocumentTest extends TestCase
{
    private const SOME_COUNTRY_CODE = "someCountryCode";
    private const SOME_DOCUMENT_TYPE = "someDocumentType";

    /**
     * @test
     * @covers ::__construct
     * @covers ::getDocumentType
     * @covers ::getCountryCode
     * @covers \Yoti\DocScan\Session\Instructions\Document\SelectedDocument::getType
     * @covers \Yoti\DocScan\Session\Instructions\Document\SelectedDocument::__construct
     * @covers \Yoti\DocScan\Session\Instructions\Document\SelectedIdDocumentBuilder::build
     * @covers \Yoti\DocScan\Session\Instructions\Document\SelectedIdDocumentBuilder::withCountryCode
     * @covers \Yoti\DocScan\Session\Instructions\Document\SelectedIdDocumentBuilder::withDocumentType
     */
    public function builderShouldBuildWithAllProperties()
    {
        $result = (new SelectedIdDocumentBuilder())
            ->withCountryCode(self::SOME_COUNTRY_CODE)
            ->withDocumentType(self::SOME_DOCUMENT_TYPE)
            ->build();

        $this->assertEquals(self::SOME_DOCUMENT_TYPE, $result->getDocumentType());
        $this->assertEquals(self::SOME_COUNTRY_CODE, $result->getCountryCode());
        $this->assertEquals("ID_DOCUMENT", $result->getType());
    }
}
