<?php

namespace YotiTest\Util\Profile;

use ArrayObject;
use Yoti\Entity\Anchor;
use YotiTest\TestCase;
use Yoti\Util\Profile\AnchorListConverter;

class AnchorListConverterTest extends TestCase
{
    public function testConvertingSourceAnchor()
    {
        $anchorsData = $this->parseFromBase64String(TestAnchors::SOURCE_PP_ANCHOR);
        $this->assertEquals('PASSPORT', $anchorsData[Anchor::TYPE_SOURCE_OID][0]->getValue());
    }

    public function testConvertingVerifierAnchor()
    {
        $anchorsData = $this->parseFromBase64String(TestAnchors::VERIFIER_YOTI_ADMIN_ANCHOR);
        $anchorVerifiersObj = $anchorsData[Anchor::TYPE_VERIFIER_OID][0];
        $this->assertEquals('YOTI_ADMIN', $anchorVerifiersObj->getValue());
    }

    public function testConvertingTwoSources()
    {
        $passportAnchor = new \Attrpubapi_v1\Anchor();
        $passportAnchor->mergeFromString(base64_decode(TestAnchors::SOURCE_PP_ANCHOR));

        $dlAnchor = new \Attrpubapi_v1\Anchor();
        $dlAnchor->mergeFromString(base64_decode(TestAnchors::SOURCE_DL_ANCHOR));

        $collection = new ArrayObject([$passportAnchor, $dlAnchor]);
        $anchorsData = AnchorListConverter::convert($collection);
        $anchorSource1 = $anchorsData[Anchor::TYPE_SOURCE_OID][0]->getValue();
        $anchorSource2 = $anchorsData[Anchor::TYPE_SOURCE_OID][1]->getValue();
        $expectedAnchors = ['PASSPORT', 'DRIVING_LICENCE'];

        $this->assertEquals(
            json_encode($expectedAnchors),
            json_encode([$anchorSource1, $anchorSource2])
        );
    }

    /**
     * @param string $anchorString
     *
     * @return array $anchors
     */
    public function parseFromBase64String($anchorString)
    {
        $anchor = new \Attrpubapi_v1\Anchor();
        $anchor->mergeFromString(base64_decode($anchorString));

        $collection = new ArrayObject([$anchor]);

        return AnchorListConverter::convert($collection);
    }
}