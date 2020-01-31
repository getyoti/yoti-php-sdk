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

    /**
     * @test
     * @covers ::__construct
     * @covers ::getManualCheck
     */
    public function shouldHoldValuesWithoutModification()
    {
        $result = new RequestedTextExtractionTaskConfig(self::SOME_MANUAL_CHECK);

        $this->assertEquals(self::SOME_MANUAL_CHECK, $result->getManualCheck());
    }

    /**
     * @test
     * @covers ::jsonSerialize
     */
    public function shouldSerializeToJsonCorrectly()
    {
        $result = new RequestedTextExtractionTaskConfig(self::SOME_MANUAL_CHECK);

        $expected = [
            'manual_check' => self::SOME_MANUAL_CHECK,
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }
}
