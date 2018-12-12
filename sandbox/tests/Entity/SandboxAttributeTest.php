<?php

namespace SandboxTest\Entity;

use YotiTest\TestCase;
use Yoti\Entity\Profile;
use YotiSandbox\Entity\SandboxAttribute;

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

    public function testGetName()
    {
        $this->assertEquals(Profile::ATTR_FAMILY_NAME, $this->attribute->getName());
    }

    public function testGetValue()
    {
        $this->assertEquals('Fake_Family_Name', $this->attribute->getValue());
    }

    public function testGetDerivation()
    {
        $this->assertEmpty($this->attribute->getDerivation());
    }

    public function testGetOptional()
    {
        $this->assertEquals(
            'false',
            $this->attribute->getOptional(),
            'Should return false as a string'
        );
    }

    public function testGetAnchors()
    {
        $this->assertEquals(
            json_encode([]),
            json_encode($this->attribute->getAnchors()),
            'Should be an empty array'
        );
    }
}