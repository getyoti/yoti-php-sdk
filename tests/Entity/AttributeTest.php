<?php
namespace YotiTest\Entity;

use Yoti\Entity\Attribute;
use YotiTest\TestCase;

class AttributeTest extends TestCase
{
    /**
     * @var Attribute
     */
    public $attribute;

    /**
     * @var string
     */
    public $attributeName;
    /**
     * @var string
     */
    public $attributeValue;
    /**
     * @var array
     */
    public $attributeSources;
    /**
     * @var array
     */
    public $attributeVerifiers;

    public function setup()
    {
        $this->attributeName = 'full_name';
        $this->attributeValue = 'Test FullName';
        $this->attributeSources = [
            'PASSPORT'
        ];
        $this->attributeVerifiers = [
            'YOTI_ADMIN'
        ];

        $this->attribute = new Attribute(
            $this->attributeName,
            $this->attributeValue,
            $this->attributeSources,
            $this->attributeVerifiers
        );
    }

    public function testSetName()
    {
        $attribute = clone $this->attribute;
        $attributeName = 'Test My Name';
        $attribute->setName($attributeName);
        $this->assertEquals($attributeName, $attribute->getName());
    }

    public function testGetName()
    {
        $this->assertEquals($this->attributeName, $this->attribute->getName());
    }

    public function testGetValue()
    {
        $this->assertEquals($this->attributeValue, $this->attribute->getValue());
    }

    public function testSetSources()
    {
        $attribute = clone $this->attribute;
        $attributeNewSources = [
            'USER_PROVIDED',
            'PASSPORT',
        ];
        $attribute->setSources($attributeNewSources);

        $this->assertEquals(
            json_encode($attributeNewSources),
            json_encode($attribute->getSources())
        );
    }

    public function testGetSources()
    {
        $this->assertEquals(
            json_encode($this->attributeSources),
            json_encode($this->attribute->getSources())
        );
    }

    public function testGetVerifiers()
    {
        $this->assertEquals(
            json_encode($this->attributeVerifiers),
            json_encode($this->attribute->getVerifiers())
        );
    }
}