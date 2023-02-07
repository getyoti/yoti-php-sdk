<?php

namespace Yoti\Test\IDV\Session\Create\Check;

use Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheckConfig;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheckConfig
 */
class RequestedDocumentAuthenticityCheckConfigTest extends TestCase
{
    private const SOME_MANUAL_CHECK = 'someManualCheck';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getManualCheck
     * @covers ::jsonSerialize
     */
    public function shouldSerializeToJsonCorrectly()
    {
        $checkConfig = new RequestedDocumentAuthenticityCheckConfig(self::SOME_MANUAL_CHECK);

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'manual_check' => self::SOME_MANUAL_CHECK,
            ]),
            json_encode($checkConfig)
        );
    }
}
