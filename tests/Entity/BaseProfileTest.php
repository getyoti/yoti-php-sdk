<?php

namespace YotiTest\Entity;

use YotiTest\TestCase;
use Yoti\Entity\Attribute;
use Yoti\Entity\BaseProfile;

/**
 * @coversDefaultClass \Yoti\Entity\BaseProfile
 */
class BaseProfileTest extends TestCase
{
    const SOME_ATTRIBUTE = 'some_attribute';
    const SOME_OTHER_ATTRIBUTE = 'some_other_attribute';
    const SOME_INVALID_ATTRIBUTE = 'some_invalid_attribute';
    const SOME_MISSING_ATTRIBUTE = 'some_missing_attribute';

    /**
     * @var \Yoti\Entity\BaseProfile
     */
    private $baseProfile;

    /**
     * @var \Yoti\Entity\Attribute
     */
    private $someAttribute;

    /**
     * @var \Yoti\Entity\Attribute
     */
    private $someOtherAttribute;

    /**
     * @var \Yoti\Entity\Attribute
     */
    private $someOtherAttributeWithSameName;

    /**
     * Setup mocks.
     */
    public function setup()
    {
        $this->someAttribute = $this->createMock(Attribute::class);
        $this->someAttribute
            ->method('getName')
            ->willReturn(self::SOME_ATTRIBUTE);

        $this->someOtherAttributeWithSameName = $this->createMock(Attribute::class);
        $this->someOtherAttributeWithSameName
            ->method('getName')
            ->willReturn(self::SOME_ATTRIBUTE);

        $this->someOtherAttribute = $this->createMock(Attribute::class);
        $this->someOtherAttribute
            ->method('getName')
            ->willReturn(self::SOME_OTHER_ATTRIBUTE);

        $someAttributeWithNullName = $this->createMock(Attribute::class);
        $someAttributeWithNullName
            ->method('getName')
            ->willReturn(null);

        $this->baseProfile = new BaseProfile([
            $this->someAttribute,
            $this->someOtherAttributeWithSameName,
            $this->someOtherAttribute,
            $someAttributeWithNullName,
            self::SOME_INVALID_ATTRIBUTE => 'invalid',
        ]);
    }

    /**
     * @covers ::__construct
     * @covers ::getProfileAttribute
     */
    public function testGetProfileAttribute()
    {
        $this->assertSame($this->someAttribute, $this->baseProfile->getProfileAttribute(self::SOME_ATTRIBUTE));
        $this->assertNull($this->baseProfile->getProfileAttribute(self::SOME_INVALID_ATTRIBUTE));
        $this->assertNull($this->baseProfile->getProfileAttribute(self::SOME_MISSING_ATTRIBUTE));
    }

    /**
     * @covers ::__construct
     * @covers ::getAttributesList
     */
    public function testGetAttributesList()
    {
        $attributesList = $this->baseProfile->getAttributesList(self::SOME_ATTRIBUTE);
        $this->assertCount(4, $attributesList);
        $this->assertContainsOnlyInstancesOf(Attribute::class, $attributesList);
    }

    /**
     * @covers ::__construct
     * @covers ::getAttributes
     */
    public function testGetAttributes()
    {
        $attributesMap = $this->baseProfile->getAttributes(self::SOME_ATTRIBUTE);
        $this->assertCount(3, $attributesMap);
        $this->assertContainsOnlyInstancesOf(Attribute::class, $attributesMap);

        // Map should contain first attribute when there are multiple with the same name.
        $this->assertSame($attributesMap[self::SOME_ATTRIBUTE], $this->someAttribute);
    }

    /**
     * @covers ::__construct
     * @covers ::getAttributesByName
     */
    public function testGetAttributesByName()
    {
        $attributes = $this->baseProfile->getAttributesByName(self::SOME_ATTRIBUTE);
        $this->assertCount(2, $attributes);
        $this->assertSame($this->someAttribute, $attributes[0]);
        $this->assertSame($this->someOtherAttributeWithSameName, $attributes[1]);

        $invalidAttributes = $this->baseProfile->getAttributesByName(self::SOME_INVALID_ATTRIBUTE);
        $this->assertIsArray($invalidAttributes);
        $this->assertEmpty($invalidAttributes);

        $missingAttributes = $this->baseProfile->getAttributesByName(self::SOME_MISSING_ATTRIBUTE);
        $this->assertIsArray($missingAttributes);
        $this->assertEmpty($missingAttributes);
    }
}
