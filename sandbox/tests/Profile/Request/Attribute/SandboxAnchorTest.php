<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Test\Profile\Request\Attribute;

use Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor
 */
class SandboxAnchorTest extends TestCase
{
    private const SOME_TYPE = 'SOURCE';
    private const SOME_VALUE = 'PASSPORT';
    private const SOME_SUB_TYPE = 'OCR';
    private const SOME_TIMESTAMP = 1544624701;

    /**
     * @var SandboxAnchor
     */
    private $anchor;

    public function setup(): void
    {
        $this->anchor = new SandboxAnchor(
            self::SOME_TYPE,
            self::SOME_VALUE,
            self::SOME_SUB_TYPE,
            self::SOME_TIMESTAMP
        );
    }

    /**
     * @covers ::jsonSerialize
     * @covers ::__construct
     */
    public function testJsonSerialize()
    {
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'type' => self::SOME_TYPE,
                'value' => self::SOME_VALUE,
                'sub_type' => self::SOME_SUB_TYPE,
                'timestamp' => self::SOME_TIMESTAMP * 1000000,
            ]),
            json_encode($this->anchor)
        );
    }
}
