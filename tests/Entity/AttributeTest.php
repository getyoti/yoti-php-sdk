<?php
namespace YotiTest\Entity;

use YotiTest\TestCase;
use Yoti\Entity\Profile;
use Yoti\Entity\Attribute;

class AttributeTest extends TestCase
{
    const ATTR_VALUE = 'Test FullName';

    protected $attributeSources = ['PASSPORT'];
    protected $attributeVerifiers = ['YOTI_ADMIN'];

    /**
     * @var Attribute
     */
    public $dummyAttribute;

    public function setup()
    {
        $this->dummyAttribute = new Attribute(
            Profile::ATTR_FULL_NAME,
            self::ATTR_VALUE,
            $this->attributeSources,
            $this->attributeVerifiers
        );
    }

    public function testAttributeName()
    {
        $this->assertEquals(Profile::ATTR_FULL_NAME, $this->dummyAttribute->getName());
    }

    public function testAttributeValue()
    {
        $this->assertEquals(self::ATTR_VALUE, $this->dummyAttribute->getValue());
    }

    public function testAttributeSources()
    {
        $this->assertEquals(
            json_encode($this->attributeSources),
            json_encode($this->dummyAttribute->getSources())
        );
    }

    public function testAttributeVerifiers()
    {
        $this->assertEquals(
            json_encode($this->attributeVerifiers),
            json_encode($this->dummyAttribute->getVerifiers())
        );
    }
}