<?php

namespace Yoti\Test\IDV\Session\Create\Check;

use Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheck;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheck
 */
class RequestedDocumentAuthenticityCheckTest extends TestCase
{
    /**
     * @test
     * @covers ::getConfig
     * @covers ::getType
     */
    public function testConfigIsOptional()
    {
        $check = new RequestedDocumentAuthenticityCheck();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'type' => 'ID_DOCUMENT_AUTHENTICITY',
            ]),
            json_encode($check)
        );
    }
}
