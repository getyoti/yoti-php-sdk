<?php

declare(strict_types=1);

namespace Yoti\Test\Profile;

use Yoti\Media\Image\Jpeg;
use Yoti\Profile\Attribute;
use Yoti\Profile\BaseProfile;
use Yoti\Profile\Util\Attribute\AttributeConverter;
use Yoti\Protobuf\Attrpubapi\Attribute as ProtobufAttribute;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\BaseProfile
 */
class BaseProfileTest extends TestCase
{
    private const SOME_ATTRIBUTE = 'some_attribute';
    private const SOME_OTHER_ATTRIBUTE = 'some_other_attribute';
    private const SOME_INVALID_ATTRIBUTE = 'some_invalid_attribute';
    private const SOME_MISSING_ATTRIBUTE = 'some_missing_attribute';
    private const SOME_ID_1 = '9e2b479a-7be9-4e88-b4ab-e47fc930af61';
    private const SOME_ID_2 = 'a8960bbb-de13-47d1-9bd3-f6f32de8505a';

    private const CONTENT_TYPE_STRING = 1;


    /**
     * @var \Yoti\Profile\BaseProfile
     */
    private $baseProfile;

    /**
     * @var Attribute
     */
    private $someAttribute;

    /**
     * @var Attribute
     */
    private $someOtherAttribute;

    /**
     * @var Attribute
     */
    private $someOtherAttributeWithSameName;

    /**
     * Setup mocks.
     */
    public function setup(): void
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

        $this->baseProfile = new BaseProfile([
            $this->someAttribute,
            $this->someOtherAttributeWithSameName,
            $this->someOtherAttribute,
        ]);
    }

    /**
     * @covers ::__construct
     * @covers ::getProfileAttribute
     * @covers ::setAttributesMap
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
        $this->assertCount(3, $attributesList);
        $this->assertContainsOnlyInstancesOf(Attribute::class, $attributesList);
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

    /**
     * @covers ::__construct
     * @covers ::getAttributeById
     */
    public function testGetAttributeById()
    {
        $imageAttribute1 = new Attribute(
            self::SOME_ATTRIBUTE,
            new Jpeg('SOME'),
            [],
            self::SOME_ID_1
        );

        $imageAttribute2 = new Attribute(
            self::SOME_ATTRIBUTE,
            new Jpeg('SOME'),
            [],
            self::SOME_ID_2
        );

        $givenNamesAttribute = new ProtobufAttribute([
            'name' => self::SOME_ATTRIBUTE,
            'value' => utf8_decode('Alan'),
            'content_type' => self::CONTENT_TYPE_STRING,
        ]);
        $newAttribute = AttributeConverter::convertToYotiAttribute($givenNamesAttribute);

        $yotiProfile = new BaseProfile([$imageAttribute1, $imageAttribute2, $newAttribute]);

        $this->assertEquals($imageAttribute1, $yotiProfile->getAttributeById(self::SOME_ID_1));
        $this->assertEquals($imageAttribute2, $yotiProfile->getAttributeById(self::SOME_ID_2));
    }

    /**
     * @covers ::__construct
     * @covers ::getAttributeById
     */
    public function testGetAttributeByIdReturnsNullWhenNotPresent()
    {
        $imageAttribute1 = new Attribute(
            self::SOME_ATTRIBUTE,
            new Jpeg('SOME'),
            [],
            self::SOME_ID_1
        );

        $yotiProfile = new BaseProfile([$imageAttribute1]);

        $this->assertNull($yotiProfile->getAttributeById('SOME-another'));
    }
}
