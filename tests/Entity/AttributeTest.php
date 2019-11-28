<?php

namespace YotiTest\Entity;

use YotiTest\TestCase;
use ArrayObject;
use Yoti\Entity\Anchor as YotiAnchor;
use Yoti\Entity\Profile;
use Yoti\Entity\Attribute;
use YotiTest\Util\Profile\TestAnchors;
use Yoti\Util\Profile\AnchorListConverter;

/**
 * @coversDefaultClass \Yoti\Entity\Attribute
 */
class AttributeTest extends TestCase
{
    const ATTR_VALUE = 'Test FullName';

    /**
     * @var \Yoti\Entity\Attribute
     */
    public $dummyAttribute;

    public function setup()
    {
        $protobufAnchors[] = $this->convertToProtobufAnchor(TestAnchors::SOURCE_DL_ANCHOR);
        $protobufAnchors[] = $this->convertToProtobufAnchor(TestAnchors::SOURCE_PP_ANCHOR);
        $protobufAnchors[] = $this->convertToProtobufAnchor(TestAnchors::VERIFIER_YOTI_ADMIN_ANCHOR);
        $protobufAnchors[] = $this->convertToProtobufAnchor(TestAnchors::UNKNOWN_ANCHOR);
        $collection = new ArrayObject($protobufAnchors);
        $yotiAnchorsMap = AnchorListConverter::convert($collection);

        $this->dummyAttribute = new Attribute(
            Profile::ATTR_FULL_NAME,
            self::ATTR_VALUE,
            $yotiAnchorsMap
        );
    }

    /**
     * @covers ::getName
     * @covers ::__construct
     */
    public function testAttributeName()
    {
        $this->assertEquals(Profile::ATTR_FULL_NAME, $this->dummyAttribute->getName());
    }

    /**
     * @covers ::getValue
     * @covers ::__construct
     */
    public function testAttributeValue()
    {
        $this->assertEquals(self::ATTR_VALUE, $this->dummyAttribute->getValue());
    }

    /**
     * @covers ::getSources
     * @covers ::__construct
     */
    public function testGetSources()
    {
        $sources = $this->dummyAttribute->getSources();

        $this->assertEquals(2, count($sources));

        $this->assertEquals(
            'DRIVING_LICENCE',
            $sources[0]->getValue()
        );
        $this->assertEquals(
            YotiAnchor::TYPE_SOURCE_NAME,
            $sources[0]->getType()
        );
    }

    /**
     * @covers ::getVerifiers
     * @covers ::__construct
     */
    public function testVerifiers()
    {
        $verifiers = $this->dummyAttribute->getVerifiers();
        $this->assertEquals(
            'YOTI_ADMIN',
            $verifiers[0]->getValue()
        );
        $this->assertEquals(
            YotiAnchor::TYPE_VERIFIER_NAME,
            $verifiers[0]->getType()
        );
    }

    /**
     * @covers ::getAnchors
     * @covers ::setSources
     * @covers ::setVerifiers
     * @covers ::setAnchors
     * @covers ::getAnchorType
     * @covers ::__construct
     */
    public function testGetAnchors()
    {
        $anchors = $this->dummyAttribute->getAnchors();
        $this->assertEquals(YotiAnchor::TYPE_SOURCE_NAME, $anchors[0]->getType());
        $this->assertEquals('DRIVING_LICENCE', $anchors[0]->getValue());
        $this->assertEquals(YotiAnchor::TYPE_SOURCE_NAME, $anchors[1]->getType());
        $this->assertEquals('PASSPORT', $anchors[1]->getValue());
        $this->assertEquals(YotiAnchor::TYPE_VERIFIER_NAME, $anchors[2]->getType());
        $this->assertEquals('YOTI_ADMIN', $anchors[2]->getValue());
        $this->assertEquals(YotiAnchor::TYPE_UNKNOWN_NAME, $anchors[3]->getType());
        $this->assertEquals('', $anchors[3]->getValue());
    }

    /**
     * Convert anchor string to Protobuf Anchor
     *
     * @param string $anchorString
     * @return \Yoti\Protobuf\Attrpubapi\Anchor
     */
    public function convertToProtobufAnchor($anchorString)
    {
        $anchor = new \Yoti\Protobuf\Attrpubapi\Anchor();
        $anchor->mergeFromString(base64_decode($anchorString));
        return $anchor;
    }
}
