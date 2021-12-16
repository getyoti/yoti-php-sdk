<?php

namespace Yoti\Test\DocScan\Session\Instructions\Document;

use Yoti\DocScan\Session\Instructions\Document\SelectedIdDocumentBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Instructions\Document\DocumentProposal
 */
class SelectedIdDocumentTest extends TestCase
{
    private const SOME_COUNTRY_CODE = "someCountryCode";
    private const SOME_DOCUMENT_TYPE = "someDocumentType";

    /**
     * @test
     * @covers ::__construct
     * @covers ::getDocument
     * @covers ::getRequirementId
     * @covers \Yoti\DocScan\Session\Instructions\Document\DocumentProposalBuilder::build
     * @covers \Yoti\DocScan\Session\Instructions\Document\DocumentProposalBuilder::withSelectedDocument
     * @covers \Yoti\DocScan\Session\Instructions\Document\DocumentProposalBuilder::withRequirementId
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
