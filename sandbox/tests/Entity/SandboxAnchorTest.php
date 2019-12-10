<?php

namespace SandboxTest\Entity;

use YotiTest\TestCase;
use YotiSandbox\Entity\SandboxAnchor;

class SandboxAnchorTest extends TestCase
{
    /**
     * @var SandboxAnchor
     */
    public $anchor;

    public function setUp()
    {
        $this->anchor = new SandboxAnchor(
            'Source',
            'PASSPORT',
            'OCR',
            1544624701 // 12-12-2018 14:25:01
        );
    }

    public function testGetType()
    {
        $this->assertEquals('Source', $this->anchor->getType());
    }

    public function testGetValue()
    {
        $this->assertEquals('PASSPORT', $this->anchor->getValue());
    }

    public function testGetSubtype()
    {
        $this->assertEquals('OCR', $this->anchor->getSubtype());
    }

    public function testGetTimestamp()
    {
        $this->assertEquals(1544624701, $this->anchor->getTimestamp());
    }
}
