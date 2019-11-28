<?php

namespace YotiTest\Entity;

use ArrayObject;
use Yoti\Entity\Anchor;
use YotiTest\TestCase;
use YotiTest\Util\Profile\TestAnchors;
use Yoti\Util\Profile\AnchorListConverter;

/**
 * @coversDefaultClass \Yoti\Entity\Anchor
 */
class AnchorTest extends TestCase
{
    /**
     * Check Anchor class exists.
     */
    public function testAnchorClassExists()
    {
        $this->assertTrue(class_exists(Anchor::class));
    }

    /**
     * @covers ::getType
     * @covers ::getSubType
     * @covers ::getValue
     * @covers ::getSignedTimeStamp
     * @covers ::getOriginServerCerts
     * @covers ::__construct
     */
    public function testYotiAnchorEndpoints()
    {
        $dlAnchor = new \Yoti\Protobuf\Attrpubapi\Anchor();
        $dlAnchor->mergeFromString(base64_decode(TestAnchors::SOURCE_DL_ANCHOR));
        $collection = new ArrayObject([$dlAnchor]);
        $anchorList = AnchorListConverter::convert($collection);

        /**
         * @var Anchor $sourceAnchor
         */
        $sourceAnchor = $anchorList[Anchor::TYPE_SOURCE_OID][0];

        $this->assertEquals(Anchor::TYPE_SOURCE_NAME, $sourceAnchor->getType());
        $this->assertEquals('', $sourceAnchor->getSubtype());
        $this->assertequals('DRIVING_LICENCE', $sourceAnchor->getValue());
        $this->assertInstanceOf(
            \DateTime::class,
            $sourceAnchor->getSignedTimeStamp()->getTimestamp()
        );
        $dateTime = $sourceAnchor->getSignedTimeStamp()->getTimestamp();
        $this->assertEquals(
            '11-04-2018 12:13:03:923537',
            $dateTime->format('d-m-Y H:i:s:u')
        );
        $this->assertInstanceOf(
            \stdClass::class,
            $sourceAnchor->getOriginServerCerts()[0]
        );
    }
}
