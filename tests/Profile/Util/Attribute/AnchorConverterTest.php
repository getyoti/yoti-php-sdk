<?php

declare(strict_types=1);

namespace Yoti\Test\Profile\Util\Attribute;

use Yoti\Profile\Attribute\Anchor;
use Yoti\Profile\Util\Attribute\AnchorConverter;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\Util\Attribute\AnchorConverter
 */
class AnchorConverterTest extends TestCase
{
    /**
     * @covers ::convert
     * @covers ::convertToYotiSignedTimestamp
     * @covers ::decodeAnchorValue
     * @covers ::getAnchorTypeByOid
     * @covers ::getAnchorTypesMap
     * @covers ::convertCertsListToX509
     * @covers ::convertCertToX509
     */
    public function testConvertingSourceAnchor()
    {
        $anchor = $this->parseFromBase64String(TestAnchors::SOURCE_PP_ANCHOR);

        $this->assertEquals('SOURCE', $anchor->getType());
        $this->assertEquals('OCR', $anchor->getSubtype());
        $this->assertEquals(
            '2018-04-12 13:14:32.835537',
            $anchor->getSignedTimestamp()->getTimestamp()->format('Y-m-d H:i:s.u')
        );
        $this->assertEquals('PASSPORT', $anchor->getValue());

        $this->assertSerialNumber($anchor, '277870515583559162487099305254898397834');
        $this->assertIssuer($anchor, 'id-at-commonName', 'passport-registration-server');
    }

    /**
     * @covers ::convert
     * @covers ::convertToYotiSignedTimestamp
     * @covers ::decodeAnchorValue
     * @covers ::getAnchorTypeByOid
     * @covers ::getAnchorTypesMap
     * @covers ::convertCertsListToX509
     * @covers ::convertCertToX509
     */
    public function testConvertingVerifierAnchor()
    {
        $anchor = $this->parseFromBase64String(TestAnchors::VERIFIER_YOTI_ADMIN_ANCHOR);

        $this->assertEquals('VERIFIER', $anchor->getType());
        $this->assertEquals('', $anchor->getSubtype());
        $this->assertEquals(
            '2018-04-11 12:13:04.095238',
            $anchor->getSignedTimestamp()->getTimestamp()->format('Y-m-d H:i:s.u')
        );
        $this->assertEquals('YOTI_ADMIN', $anchor->getValue());

        $this->assertSerialNumber($anchor, '256616937783084706710155170893983549581');
        $this->assertIssuer($anchor, 'id-at-commonName', 'driving-licence-registration-server');
    }

    /**
     * @covers ::convert
     */
    public function testConvertingUnknownAnchor()
    {
        $anchor = $this->parseFromBase64String(TestAnchors::UNKNOWN_ANCHOR);

        $this->assertEquals('UNKNOWN', $anchor->getType());
        $this->assertEquals('TEST UNKNOWN SUB TYPE', $anchor->getSubtype());
        $this->assertEquals(
            '2019-03-05 10:45:11.840037',
            $anchor->getSignedTimestamp()->getTimestamp()->format('Y-m-d H:i:s.u')
        );
        $this->assertEquals('', $anchor->getValue());

        $this->assertSerialNumber($anchor, '228164395157066285041920465780950248577');
        $this->assertIssuer($anchor, 'id-at-commonName', 'document-registration-server');
    }

    /**
     * @param string $anchorString
     *
     * @return Anchor
     */
    private function parseFromBase64String($anchorString)
    {
        $anchor = new \Yoti\Protobuf\Attrpubapi\Anchor();
        $anchor->mergeFromString(base64_decode($anchorString));
        return AnchorConverter::convert($anchor);
    }

    /**
     * @param Anchor $anchor
     * @param string $serial_number
     */
    private function assertSerialNumber($anchor, $serial_number)
    {
        $cert = $anchor->getOriginServerCerts()[0];
        $this->assertSame($serial_number, $cert->tbsCertificate->serialNumber->value);
    }

    /**
     * @param Anchor $anchor
     * @param string $type
     * @param string $value
     */
    private function assertIssuer($anchor, $type, $value)
    {
        $cert = $anchor->getOriginServerCerts()[0];
        $issuer = $cert->tbsCertificate->issuer;
        $this->assertEquals($type, $issuer->rdnSequence[0][0]->type);
        $this->assertEquals($value, $issuer->rdnSequence[0][0]->value->printableString);
    }

    /**
     * @covers ::convert
     * @covers ::decodeAnchorValue
     */
    public function testEmptyAnchorValue()
    {
        $anchor = $this->parseFromBase64String(TestAnchors::ANCHOR_NO_VALUE);

        $this->assertEquals('', $anchor->getValue());
        $this->assertEquals('SOURCE', $anchor->getType());
    }
}
