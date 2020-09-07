<?php

namespace Yoti\Test\DocScan\Session\Create\Check;

use Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheck;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheck
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
