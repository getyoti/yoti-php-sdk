<?php

namespace SandboxTest\Entity;

use Yoti\Profile\Profile;
use YotiSandbox\Entity\SandboxAttribute;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \YotiSandbox\Entity\SandboxAttribute
 */
class SandboxAttributeTest extends TestCase
{
    /**
     * @var SandboxAttribute
     */
    public $attribute;

    public function setUp()
    {
        $this->attribute = new SandboxAttribute(
            Profile::ATTR_FAMILY_NAME,
            'Fake_Family_Name',
            ''
        );
    }

    /**
     * @covers ::getName
     * @covers ::__construct
     */
    public function testGetName()
    {
        $this->assertEquals(Profile::ATTR_FAMILY_NAME, $this->attribute->getName());
    }

    /**
     * @covers ::getValue
     * @covers ::__construct
     */
    public function testGetValue()
    {
        $this->assertEquals('Fake_Family_Name', $this->attribute->getValue());
    }

    /**
     * @covers ::getDerivation
     * @covers ::__construct
     */
    public function testGetDerivation()
    {
        $this->assertEmpty($this->attribute->getDerivation());
    }

    /**
     * @covers ::getOptional
     * @covers ::__construct
     */
    public function testGetOptional()
    {
        $this->assertEquals(
            'false',
            $this->attribute->getOptional(),
            'Should return false as a string'
        );
    }

    /**
     * @covers ::getAnchors
     * @covers ::__construct
     */
    public function testGetAnchors()
    {
        $this->assertEquals(
            json_encode([]),
            json_encode($this->attribute->getAnchors()),
            'Should be an empty array'
        );
    }
}
