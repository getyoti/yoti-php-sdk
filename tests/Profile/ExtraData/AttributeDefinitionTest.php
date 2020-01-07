<?php

namespace YotiTest\Profile\ExtraData;

use YotiTest\TestCase;
use Yoti\Profile\ExtraData\AttributeDefinition;

/**
 * @coversDefaultClass \Yoti\Profile\ExtraData\AttributeDefinition
 */
class AttributeDefinitionTest extends TestCase
{
    const SOME_NAME = 'some name';

    /**
     * @var \Yoti\Profile\ExtraData\AttributeDefinition
     */
    private $attributeDefinition;

    public function setup()
    {
        $this->attributeDefinition = new AttributeDefinition(self::SOME_NAME);
    }

    /**
     * @covers ::__construct
     * @covers ::getName
     */
    public function testGetName()
    {
        $this->assertEquals(self::SOME_NAME, $this->attributeDefinition->getName());
    }

    /**
     * @covers ::__construct
     *
     * @dataProvider invalidNameDataProvider
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage name must be a string
     */
    public function testInvalidName($invalidName)
    {
        new AttributeDefinition($invalidName);
    }

    /**
     * Provides invalid names.
     *
     * @return array
     */
    public function invalidNameDataProvider()
    {
        return [
            [ 1 ],
            [ 0 ],
            [ true ],
            [ false ],
            [ [] ],
            [ (object)[] ],
        ];
    }

    /**
     * @covers ::jsonSerialize
     * @covers ::__toString
     */
    public function testJsonSerialize()
    {
        $expectedJson = json_encode(['name' => self::SOME_NAME]);

        $this->assertEquals(
            $expectedJson,
            json_encode($this->attributeDefinition)
        );

        $this->assertEquals(
            $expectedJson,
            $this->attributeDefinition
        );
    }
}
