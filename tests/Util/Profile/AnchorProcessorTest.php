<?php

namespace YotiTest\Util\Profile;

use YotiTest\TestCase;
use Yoti\Util\Profile\AnchorProcessor;

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

    public function testAnchorValuesAreUnique()
    {
        $stream = \Protobuf\Stream::fromString(base64_decode(TestAnchors::SOURCE_PP_ANCHOR));
        $anchor = \attrpubapi_v1\Anchor::fromStream($stream);
        $collection = new \Protobuf\MessageCollection([$anchor,$anchor]);
        $anchorsData = $this->anchorProcessor->process($collection);

        $anchorSources = [];
        foreach($anchorsData['sources'] as $anchorObj) {
            $anchorSources[] = $anchorObj->getValue();
        }

        $this->assertEquals(
            json_encode(['PASSPORT']),
            json_encode($anchorSources)
        );
    }

    public function testGettingTwoSourceAnchors()
    {
        $passportStream = \Protobuf\Stream::fromString(base64_decode(TestAnchors::SOURCE_PP_ANCHOR));
        $passportAnchor = \attrpubapi_v1\Anchor::fromStream($passportStream);

        $dlStream = \Protobuf\Stream::fromString(base64_decode(TestAnchors::SOURCE_DL_ANCHOR));
        $dlAnchor = \attrpubapi_v1\Anchor::fromStream($dlStream);

        $collection = new \Protobuf\MessageCollection([$passportAnchor, $dlAnchor]);
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
        $stream = \Protobuf\Stream::fromString(base64_decode($anchorString));
        $anchor = \attrpubapi_v1\Anchor::fromStream($stream);
        $collection = new \Protobuf\MessageCollection([$anchor]);

        return $this->anchorProcessor->process($collection);
    }
}