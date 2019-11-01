<?php

namespace YotiTest\Entity;

use YotiTest\TestCase;
use Yoti\Entity\AttributeDefinition;

/**
 * @coversDefaultClass \Yoti\Entity\AttributeDefinition
 */
class AttributeDefinitionTest extends TestCase
{
    const SOME_NAME = 'some name';

    /**
     * @covers ::__construct
     * @covers ::getName
     */
    public function testGetName()
    {
        $attributeDefinition = new AttributeDefinition(self::SOME_NAME);
        $this->assertEquals(self::SOME_NAME, $attributeDefinition->getName());
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
}
