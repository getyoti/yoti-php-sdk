<?php

namespace Yoti\Test\DocScan\Session\Create\Task;

use Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTaskConfig;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTaskConfig
 */
class RequestedTextExtractionTaskConfigTest extends TestCase
{
    private const SOME_MANUAL_CHECK = 'someManualCheck';
    private const SOME_CHIP_DATA = 'someChipData';
    private const SOME_EXPANDED_DOCUMENT_FIELDS = false;


    /**
     * @test
     * @covers ::__construct
     * @covers ::jsonSerialize
     * @covers ::getManualCheck
     */
    public function shouldSerializeToJsonCorrectlyWithRequiredProperties()
    {
        $result = new RequestedTextExtractionTaskConfig(self::SOME_MANUAL_CHECK);

        $expected = [
            'manual_check' => self::SOME_MANUAL_CHECK,
            'create_expanded_document_fields' => self::SOME_EXPANDED_DOCUMENT_FIELDS
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::jsonSerialize
     * @covers ::getManualCheck
     * @covers ::getChipData
     */
    public function shouldSerializeToJsonCorrectlyWithAllProperties()
    {
        $result = new RequestedTextExtractionTaskConfig(self::SOME_MANUAL_CHECK, self::SOME_CHIP_DATA);

        $expected = [
            'manual_check' => self::SOME_MANUAL_CHECK,
            'chip_data' => self::SOME_CHIP_DATA,
            'create_expanded_document_fields' => self::SOME_EXPANDED_DOCUMENT_FIELDS
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }
}
