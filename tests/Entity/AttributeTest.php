<?php
namespace YotiTest\Entity;

use Yoti\Entity\Attribute;
use YotiTest\TestCase;

class AttributeTest extends TestCase
{
    const ATTR_NAME = 'full_name';

    const ATTR_VALUE = 'Test FullName';

    const ATTR_SOURCES = [
        'PASSPORT'
    ];

    const ATTR_VERIFIERS = [
        'YOTI_ADMIN'
    ];

    /**
     * @var Attribute
     */
    public $dummyAttribute;

    public function setup()
    {
        $this->dummyAttribute = new Attribute(
            self::ATTR_NAME,
            self::ATTR_VALUE,
            self::ATTR_SOURCES,
            self::ATTR_VERIFIERS
        );
    }

    public function testAttributeName()
    {
        $this->assertEquals(self::ATTR_NAME, $this->dummyAttribute->getName());
    }

    public function testAttributeValue()
    {
        $this->assertEquals(self::ATTR_VALUE, $this->dummyAttribute->getValue());
    }

    public function testAttributeSources()
    {
        $this->assertEquals(
            json_encode(self::ATTR_SOURCES),
            json_encode($this->dummyAttribute->getSources())
        );
    }

    public function testAttributeVerifiers()
    {
        $this->assertEquals(
            json_encode(self::ATTR_VERIFIERS),
            json_encode($this->dummyAttribute->getVerifiers())
        );
    }
}