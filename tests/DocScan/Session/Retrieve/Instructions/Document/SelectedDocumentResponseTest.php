<?php

namespace Yoti\Test\DocScan\Session\Retrieve\Instructions\Document;

use Yoti\DocScan\Session\Retrieve\Instructions\Document\SelectedIdDocumentResponse;
use Yoti\DocScan\Session\Retrieve\Instructions\Document\SelectedSupplementaryDocumentResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\Instructions\Document\SelectedDocumentResponse
 */
class SelectedDocumentResponseTest extends TestCase
{
    private const SOME_TYPE = "SOME_TYPE";
    private const SOME_COUNTRY_CODE = "SOME_COUNTRY_CODE";
    private const SOME_DOCUMENT_TYPE = "SOME_DOCUMENT_TYPE";

    /**
     * @test
     * @covers ::getCountryCode
     * @covers ::getType
     * @covers ::getDocumentType
     * @covers ::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\Instructions\Document\SelectedIdDocumentResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\Instructions\Document\SelectedSupplementaryDocumentResponse::__construct
     */
    public function shouldBuildCorrectly(): void
    {
        $data = [
            'type' => self::SOME_TYPE,
            'country_code' => self::SOME_COUNTRY_CODE,
            'document_type' => self::SOME_DOCUMENT_TYPE
        ];

        $result = new SelectedIdDocumentResponse($data);
        $this->assertEquals(self::SOME_TYPE, $result->getType());
        $this->assertEquals(self::SOME_DOCUMENT_TYPE, $result->getDocumentType());
        $this->assertEquals(self::SOME_COUNTRY_CODE, $result->getCountryCode());

        $result2 = new SelectedSupplementaryDocumentResponse($data);
        $this->assertEquals(self::SOME_TYPE, $result2->getType());
        $this->assertEquals(self::SOME_DOCUMENT_TYPE, $result2->getDocumentType());
        $this->assertEquals(self::SOME_COUNTRY_CODE, $result2->getCountryCode());
    }
}
