<?php

namespace YotiTest\Util\Profile;

use YotiTest\TestCase;
use Yoti\Util\Profile\AnchorProcessor;
use ArrayObject;

class AnchorProcessorTest extends TestCase
{
    public $anchorProcessor;

    public function setup()
    {
        $this->anchorProcessor = new AnchorProcessor();
    }

    public function testSourceAnchor()
    {
        $anchorsData = $this->parseFromBase64String(TestAnchors::SOURCE_PP_ANCHOR);
        $this->assertEquals('PASSPORT', $anchorsData['sources'][0]->getValue());
    }

    public function testVerifierAnchor()
    {
        $anchorsData = $this->parseFromBase64String(TestAnchors::VERIFIER_YOTI_ADMIN_ANCHOR);
        $anchorVerifiersObj = $anchorsData['verifiers'][0];
        $this->assertEquals('YOTI_ADMIN', $anchorVerifiersObj->getValue());
    }

    public function testGettingTwoSourceAnchors()
    {
        $passportAnchor = new \Attrpubapi_v1\Anchor();
        $passportAnchor->mergeFromString(base64_decode(TestAnchors::SOURCE_PP_ANCHOR));

        $dlAnchor = new \Attrpubapi_v1\Anchor();
        $dlAnchor->mergeFromString(base64_decode(TestAnchors::SOURCE_DL_ANCHOR));

        $collection = new ArrayObject([$passportAnchor, $dlAnchor]);
        $anchorsData = $this->anchorProcessor->process($collection);
        $anchorSource1 = $anchorsData['sources'][0]->getValue();
        $anchorSource2 = $anchorsData['sources'][1]->getValue();
        $expectedAnchors = ['PASSPORT', 'DRIVING_LICENCE'];

        $this->assertEquals(
            json_encode($expectedAnchors),
            json_encode([$anchorSource1, $anchorSource2])
        );
    }

    /**
     * @param $anchorString
     * @return array $anchors
     */
    public function parseFromBase64String($anchorString)
    {
        $anchor = new \Attrpubapi_v1\Anchor();
        $anchor->mergeFromString(base64_decode($anchorString));

        $collection = new ArrayObject([$anchor]);

        return $this->anchorProcessor->process($collection);
    }
}