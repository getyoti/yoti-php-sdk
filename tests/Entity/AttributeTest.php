<?php
namespace YotiTest\Entity;

use YotiTest\TestCase;
use ArrayObject;
use Yoti\Entity\Anchor as YotiAnchor;
use Yoti\Entity\Profile;
use Yoti\Entity\Attribute;
use YotiTest\Util\Profile\TestAnchors;
use Yoti\Util\Profile\AnchorListConverter;

class AttributeTest extends TestCase
{
    const ATTR_VALUE = 'Test FullName';

    /**
     * @var Attribute
     */
    public $dummyAttribute;

    public function setup()
    {
        $protobufAnchors[] = $this->convertToProtobufAnchor(TestAnchors::SOURCE_DL_ANCHOR);
        $protobufAnchors[] = $this->convertToProtobufAnchor(TestAnchors::SOURCE_PP_ANCHOR);
        $protobufAnchors[] = $this->convertToProtobufAnchor(TestAnchors::VERIFIER_YOTI_ADMIN_ANCHOR);
        $collection = new ArrayObject($protobufAnchors);
        $yotiAnchorsMap = AnchorListConverter::convert($collection);

        $this->dummyAttribute = new Attribute(
            Profile::ATTR_FULL_NAME,
            self::ATTR_VALUE,
            $yotiAnchorsMap
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

    public function testGetAnchors()
    {
        $anchors = $this->dummyAttribute->getAnchors();
        $this->assertEquals(3, count($anchors));
        $this->assertEquals(YotiAnchor::TYPE_SOURCE_NAME, $anchors[0]->getType());
        $this->assertEquals('DRIVING_LICENCE', $anchors[0]->getValue());
        $this->assertEquals(YotiAnchor::TYPE_SOURCE_NAME, $anchors[1]->getType());
        $this->assertEquals('PASSPORT', $anchors[1]->getValue());
        $this->assertEquals(YotiAnchor::TYPE_VERIFIER_NAME, $anchors[2]->getType());
        $this->assertEquals('YOTI_ADMIN', $anchors[2]->getValue());
    }

    public function convertToProtobufAnchor($anchorString)
    {
        $anchor = new \Attrpubapi_v1\Anchor();
        $anchor->mergeFromString(base64_decode($anchorString));
        return $anchor;
    }
}