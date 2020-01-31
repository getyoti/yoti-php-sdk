<?php

declare(strict_types=1);

namespace Yoti\Test\Profile\Util\Attribute;

use Yoti\Profile\Util\Attribute\AnchorListConverter;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\Util\Attribute\AnchorListConverter
 */
class AnchorListConverterTest extends TestCase
{
    /**
     * @covers ::convert
     */
    public function testConvertingTwoSources()
    {
        $anchors = AnchorListConverter::convert(new \ArrayObject([
            $this->parseFromBase64String(TestAnchors::SOURCE_PP_ANCHOR),
            $this->parseFromBase64String(TestAnchors::SOURCE_DL_ANCHOR),
        ]));

        $anchorSource1 = $anchors[0];
        $anchorSource2 = $anchors[1];

        $this->assertEquals('PASSPORT', $anchorSource1->getValue());
        $this->assertEquals('DRIVING_LICENCE', $anchorSource2->getValue());
    }

    /**
     * @covers ::convert
     */
    public function testConvertingAnyAnchor()
    {
        $anchors = AnchorListConverter::convert(new \ArrayObject([
            $this->parseFromBase64String(TestAnchors::SOURCE_DL_ANCHOR),
            $this->parseFromBase64String(TestAnchors::VERIFIER_YOTI_ADMIN_ANCHOR),
            $this->parseFromBase64String(TestAnchors::UNKNOWN_ANCHOR),
        ]));

        $anchorSource = $anchors[0];
        $this->assertEquals('SOURCE', $anchorSource->getType());
        $this->assertEquals('DRIVING_LICENCE', $anchorSource->getValue());

        $anchorVerifier = $anchors[1];
        $this->assertEquals('VERIFIER', $anchorVerifier->getType());
        $this->assertEquals('YOTI_ADMIN', $anchorVerifier->getValue());

        $anchorUnknown = $anchors[2];
        $this->assertEquals('UNKNOWN', $anchorUnknown->getType());
        $this->assertEquals('', $anchorUnknown->getValue());
    }

    /**
     * @param string $anchorString
     *
     * @return array $anchors
     */
    private function parseFromBase64String($anchorString)
    {
        $anchor = new \Yoti\Protobuf\Attrpubapi\Anchor();
        $anchor->mergeFromString(base64_decode($anchorString));
        return $anchor;
    }
}
