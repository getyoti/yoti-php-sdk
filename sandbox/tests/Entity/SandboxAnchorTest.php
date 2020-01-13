<?php

namespace SandboxTest\Entity;

use YotiSandbox\Entity\SandboxAnchor;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \YotiSandbox\Entity\SandboxAnchor
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
