<?php

namespace Yoti\Test\IDV\Session\Create\Task;

use Yoti\IDV\Session\Create\Task\RequestedSupplementaryDocTextExtractionTaskConfig;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Create\Task\RequestedSupplementaryDocTextExtractionTaskConfig
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
