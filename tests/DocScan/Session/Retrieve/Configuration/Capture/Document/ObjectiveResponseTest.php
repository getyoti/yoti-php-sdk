<?php

namespace Yoti\Test\DocScan\Session\Retrieve\Configuration\Capture\Document;

use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document\ObjectiveResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document\ObjectiveResponse
 */
class ObjectiveResponseTest extends TestCase
{
    private const SOME_TYPE = 'SOME-TYPE';
    /**
     * @test
     * @covers ::__construct
     * @covers ::getType
     */
    public function shouldBuildCorrectly()
    {
        $result = new ObjectiveResponse(['type' => self::SOME_TYPE]);

        $this->assertEquals(self::SOME_TYPE, $result->getType());
        $this->assertInstanceOf(ObjectiveResponse::class, $result);
    }
}
