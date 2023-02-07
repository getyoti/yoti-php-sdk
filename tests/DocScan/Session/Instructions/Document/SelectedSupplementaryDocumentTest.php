<?php

namespace Yoti\Test\IDV\Session\Instructions\Document;

use Yoti\IDV\Session\Instructions\Document\SelectedSupplementaryDocumentBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Instructions\Document\SelectedSupplementaryDocument
 */
class SelectedSupplementaryDocumentTest extends TestCase
{
    private const SOME_COUNTRY_CODE = "someCountryCode";
    private const SOME_DOCUMENT_TYPE = "someDocumentType";

    /**
     * @test
     * @covers ::__construct
     * @covers ::getCountryCode
     * @covers ::getDocumentType
     * @covers \Yoti\IDV\Session\Instructions\Document\SelectedDocument::getType
     * @covers \Yoti\IDV\Session\Instructions\Document\SelectedDocument::__construct
     * @covers \Yoti\IDV\Session\Instructions\Document\SelectedSupplementaryDocumentBuilder::build
     * @covers \Yoti\IDV\Session\Instructions\Document\SelectedSupplementaryDocumentBuilder::withDocumentType
     * @covers \Yoti\IDV\Session\Instructions\Document\SelectedSupplementaryDocumentBuilder::withCountryCode
     */
    public function builderShouldBuildWithAllProperties()
    {
        $result = (new SelectedSupplementaryDocumentBuilder())
            ->withCountryCode(self::SOME_COUNTRY_CODE)
            ->withDocumentType(self::SOME_DOCUMENT_TYPE)
            ->build();

        $this->assertEquals(self::SOME_DOCUMENT_TYPE, $result->getDocumentType());
        $this->assertEquals(self::SOME_COUNTRY_CODE, $result->getCountryCode());
        $this->assertEquals("SUPPLEMENTARY_DOCUMENT", $result->getType());
    }
}
