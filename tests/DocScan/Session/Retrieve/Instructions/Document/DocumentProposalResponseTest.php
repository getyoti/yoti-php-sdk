<?php

namespace Yoti\Test\DocScan\Session\Retrieve\Instructions\Document;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Retrieve\Instructions\Document\DocumentProposalResponse;
use Yoti\DocScan\Session\Retrieve\Instructions\Document\SelectedIdDocumentResponse;
use Yoti\DocScan\Session\Retrieve\Instructions\Document\SelectedSupplementaryDocumentResponse;
use Yoti\DocScan\Session\Retrieve\Instructions\Document\UnknownDocumentResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\Instructions\Document\DocumentProposalResponse
 */
class DocumentProposalResponseTest extends TestCase
{
    private const SOME_REQUIREMENT_ID = "SOME_ID";
    private const SOME_COUNTRY_CODE = "SOME_COUNTRY_CODE";
    private const SOME_DOCUMENT_TYPE = "SOME_DOCUMENT_TYPE";


    /**
     * @test
     * @covers ::getRequirementId
     * @covers ::getDocument
     * @covers ::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\Instructions\Document\SelectedIdDocumentResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\Instructions\Document\SelectedSupplementaryDocumentResponse::__construct
     */
    public function shouldBuildCorrectly(): void
    {
        $data = [
            'requirement_id' => self::SOME_REQUIREMENT_ID,
            'document' => [
                'type' => Constants::ID_DOCUMENT,
                'country_code' => self::SOME_COUNTRY_CODE,
                'document_type' => self::SOME_DOCUMENT_TYPE
            ],
        ];

        $data2 = [
            'requirement_id' => self::SOME_REQUIREMENT_ID,
            'document' => [
                'type' => Constants::SUPPLEMENTARY_DOCUMENT,
                'country_code' => self::SOME_COUNTRY_CODE,
                'document_type' => self::SOME_DOCUMENT_TYPE
            ],
        ];

        $data3 = [
            'requirement_id' => self::SOME_REQUIREMENT_ID,
            'document' => [
                'type' => 'SOME_TYPE',
                'country_code' => self::SOME_COUNTRY_CODE,
                'document_type' => self::SOME_DOCUMENT_TYPE
            ],
        ];

        $result = new DocumentProposalResponse($data);

        $this->assertEquals(self::SOME_REQUIREMENT_ID, $result->getRequirementId());
        $this->assertInstanceOf(SelectedIdDocumentResponse::class, $result->getDocument());

        $result2 = new DocumentProposalResponse($data2);
        $this->assertEquals(self::SOME_REQUIREMENT_ID, $result2->getRequirementId());
        $this->assertInstanceOf(SelectedSupplementaryDocumentResponse::class, $result2->getDocument());

        $result3 = new DocumentProposalResponse($data3);
        $this->assertEquals(self::SOME_REQUIREMENT_ID, $result3->getRequirementId());
        $this->assertInstanceOf(UnknownDocumentResponse::class, $result3->getDocument());
    }
}
