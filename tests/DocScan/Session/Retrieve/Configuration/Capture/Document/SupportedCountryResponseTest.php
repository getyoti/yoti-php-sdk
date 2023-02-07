<?php

namespace Yoti\Test\IDV\Session\Retrieve\Configuration\Capture\Document;

use Yoti\IDV\Session\Retrieve\Configuration\Capture\Document\SupportedCountryResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Retrieve\Configuration\Capture\Document\SupportedCountryResponse
 */
class SupportedCountryResponseTest extends TestCase
{
    private const SOME_CODE = 'SOME_CODE';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getCode
     * @covers ::getSupportedDocuments
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'code' => self::SOME_CODE,
            'supported_documents' => [
                ['type' => 'ONE_TYPE'],
                ['type' => 'SECOND_TYPE'],
            ]
        ];

        $result = new SupportedCountryResponse($input);

        $this->assertEquals(self::SOME_CODE, $result->getCode());
        $this->assertCount(2, $result->getSupportedDocuments());
    }
}
