<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\DocumentFieldsResponse;
use Yoti\DocScan\Session\Retrieve\DocumentIdPhotoResponse;
use Yoti\DocScan\Session\Retrieve\IdDocumentResourceResponse;
use Yoti\DocScan\Session\Retrieve\TextExtractionTaskResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\IdDocumentResourceResponse
 */
class IdDocumentResourceResponseTest extends TestCase
{
    private const ID_DOCUMENT_TEXT_DATA_EXTRACTION = 'ID_DOCUMENT_TEXT_DATA_EXTRACTION';
    private const SOME_DOCUMENT_TYPE = 'someDocumentType';
    private const SOME_ISSUING_COUNTRY = 'someIssuingCountry';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getDocumentType
     * @covers ::getIssuingCountry
     * @covers ::getPages
     * @covers ::getDocumentFields
     * @covers ::getDocumentIdPhoto
     * @covers ::getTextExtractionTasks
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'document_type' => self::SOME_DOCUMENT_TYPE,
            'issuing_country' => self::SOME_ISSUING_COUNTRY,
            'document_id_photo' => [],
            'pages' => [
                [ 'someKey' => 'someValue' ],
                [ 'someOtherKey' => 'someOtherValue' ]
            ],
            'document_fields' => [ ], // Keys don't need to exist
            'tasks' => [
                [
                    'type' => self::ID_DOCUMENT_TEXT_DATA_EXTRACTION
                ],
                [
                    'type' => 'UNKNOWN'
                ],
            ],
        ];

        $result = new IdDocumentResourceResponse($input);

        $this->assertEquals(self::SOME_DOCUMENT_TYPE, $result->getDocumentType());
        $this->assertEquals(self::SOME_ISSUING_COUNTRY, $result->getIssuingCountry());
        $this->assertCount(2, $result->getPages());
        $this->assertInstanceOf(DocumentFieldsResponse::class, $result->getDocumentFields());
        $this->assertInstanceOf(DocumentIdPhotoResponse::class, $result->getDocumentIdPhoto());
        $this->assertCount(1, $result->getTextExtractionTasks());
        $this->assertContainsOnlyInstancesOf(
            TextExtractionTaskResponse::class,
            $result->getTextExtractionTasks()
        );
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenMissingValues()
    {
        $result = new IdDocumentResourceResponse([]);

        $this->assertNull($result->getDocumentType());
        $this->assertNull($result->getIssuingCountry());
        $this->assertCount(0, $result->getPages());
        $this->assertNull($result->getDocumentFields());
        $this->assertNull($result->getDocumentIdPhoto());
    }
}
