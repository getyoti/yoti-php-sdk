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
        $this->assertEquals('PASSPORT', $anchorsData['sources'][0]);
    }

    public function testVerifierAnchor()
    {
        $anchorsData = $this->parseFromBase64String(TestAnchors::VERIFIER_YOTI_ADMIN_ANCHOR);
        $this->assertEquals('YOTI_ADMIN', $anchorsData['verifiers'][0]);
    }

    public function testAnchorValuesAreUnique()
    {
        $stream = \Protobuf\Stream::fromString(base64_decode(TestAnchors::SOURCE_PP_ANCHOR));
        $anchor = \attrpubapi_v1\Anchor::fromStream($stream);
        $collection = new \Protobuf\MessageCollection([$anchor,$anchor]);
        $anchorsData = $this->anchorProcessor->process($collection);

        $this->assertEquals(
            json_encode(['PASSPORT']),
            json_encode($anchorsData['sources'])
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
        $expectedAnchors = ['PASSPORT', 'DRIVING_LICENCE'];

        $this->assertEquals(
            json_encode($expectedAnchors),
            json_encode($anchorsData['sources'])
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