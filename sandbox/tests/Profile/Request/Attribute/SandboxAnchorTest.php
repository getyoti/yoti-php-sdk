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
    /**
     * @var SandboxAnchor
     */
    public $anchor;

    public function setup(): void
    {
        $this->anchor = new SandboxAnchor(
            'Source',
            'PASSPORT',
            'OCR',
            1544624701 // 12-12-2018 14:25:01
        );
    }

    /**
     * @covers ::getType
     * @covers ::__construct
     */
    public function testGetType()
    {
        $this->assertEquals('Source', $this->anchor->getType());
    }

    /**
     * @covers ::getValue
     * @covers ::__construct
     */
    public function testGetValue()
    {
        $this->assertEquals('PASSPORT', $this->anchor->getValue());
    }

    /**
     * @covers ::getSubtype
     * @covers ::__construct
     */
    public function testGetSubtype()
    {
        $this->assertEquals('OCR', $this->anchor->getSubtype());
    }

    /**
     * @covers ::getTimestamp
     * @covers ::__construct
     */
    public function testGetTimestamp()
    {
        $this->assertEquals(1544624701, $this->anchor->getTimestamp());
    }
}
