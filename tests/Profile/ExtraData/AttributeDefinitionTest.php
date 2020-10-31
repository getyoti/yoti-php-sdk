<?php

declare(strict_types=1);

namespace Yoti\Test\Profile\ExtraData;

use Yoti\Profile\ExtraData\AttributeDefinition;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\ExtraData\AttributeDefinition
 */
class AttributeDefinitionTest extends TestCase
{
    private const SOME_NAME = 'some name';

    /**
     * @var \Yoti\Profile\ExtraData\AttributeDefinition
     */
    private $attributeDefinition;

    public function setup(): void
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
     */
    public function testInvalidName($invalidName)
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(sprintf('%s::__construct()', AttributeDefinition::class));

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
