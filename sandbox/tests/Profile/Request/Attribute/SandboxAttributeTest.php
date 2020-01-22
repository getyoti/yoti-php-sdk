<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Test\Profile\Request\Attribute;

use Yoti\Profile\UserProfile;
use Yoti\Sandbox\Profile\Request\Attribute\SandboxAttribute;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Sandbox\Profile\Request\Attribute\SandboxAttribute
 */
class SandboxAttributeTest extends TestCase
{
    /**
     * @var SandboxAttribute
     */
    public $attribute;

    public function setup(): void
    {
        $this->attribute = new SandboxAttribute(
            UserProfile::ATTR_FAMILY_NAME,
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
        $this->assertEquals(UserProfile::ATTR_FAMILY_NAME, $this->attribute->getName());
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
