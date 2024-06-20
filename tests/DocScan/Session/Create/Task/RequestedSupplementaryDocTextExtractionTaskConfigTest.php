<?php

namespace Yoti\Test\DocScan\Session\Create\Task;

use Yoti\DocScan\Session\Create\Task\RequestedSupplementaryDocTextExtractionTaskConfig;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Task\RequestedSupplementaryDocTextExtractionTaskConfig
 */
class RequestedSupplementaryDocTextExtractionTaskConfigTest extends TestCase
{
    private const SOME_MANUAL_CHECK = 'someManualCheck';

    /**
     * @test
     * @covers ::__construct
     * @covers ::jsonSerialize
     * @covers ::getManualCheck
     */
    public function shouldSerializeToJsonCorrectlyWithRequiredProperties()
    {
        $result = new RequestedSupplementaryDocTextExtractionTaskConfig(self::SOME_MANUAL_CHECK);

        $expected = [
            'manual_check' => self::SOME_MANUAL_CHECK,
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }
}
