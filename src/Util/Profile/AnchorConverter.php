<?php

namespace Yoti\Util\Profile;

use Traversable;
use phpseclib\File\ASN1;
use phpseclib\File\X509;
use Yoti\Entity\Anchor;
use Yoti\Protobuf\Attrpubapi\Anchor as ProtobufAnchor;

class AnchorConverter
{
    /**
     * Convert Protobuf Anchor to a map of oid -> Yoti Anchor
     *
     * @param \Yoti\Protobuf\Attrpubapi\Anchor $anchor
     *
     * @return array
     */
    public static function convert(ProtobufAnchor $protobufAnchor)
    {
        $anchorSubType = $protobufAnchor->getSubType();
        $yotiSignedTimeStamp = self::convertToYotiSignedTimestamp($protobufAnchor);
        $X509CertsList = self::convertCertsListToX509($protobufAnchor->getOriginServerCerts());

        foreach ($X509CertsList as $certX509Obj) {
            foreach ($certX509Obj->tbsCertificate->extensions as $extObj) {
                $anchorType = self::getAnchorTypeByOid($extObj->extnId);
                if ($anchorType !== Anchor::TYPE_UNKNOWN_NAME) {
                    return [
                        'oid' => $extObj->extnId,
                        'yoti_anchor' => new Anchor(
                            self::decodeAnchorValue($extObj->extnValue),
                            $anchorType,
                            $anchorSubType,
                            $yotiSignedTimeStamp,
                            $X509CertsList
                        ),
                    ];
                }
            }
        }

        return [
            'oid' => Anchor::TYPE_UNKNOWN_NAME,
            'yoti_anchor' => new Anchor(
                '',
                Anchor::TYPE_UNKNOWN_NAME,
                $anchorSubType,
                $yotiSignedTimeStamp,
                $X509CertsList
            ),
        ];
    }

    /**
     * @param $extEncodedValue
     *
     * @return null|string
     */
    private static function decodeAnchorValue($extEncodedValue)
    {
        $X509 = new X509();
        $ASN1 = new ASN1();
        $encodedBER = $X509->_extractBER($extEncodedValue);
        $decodedValArr = $ASN1->decodeBER($encodedBER);
        if (isset($decodedValArr[0]['content'][0]['content'])) {
            return $decodedValArr[0]['content'][0]['content'];
        }
        return null;
    }

    /**
     * @param \Yoti\Protobuf\Attrpubapi\Anchor $anchor
     *
     * @return \Yoti\Entity\SignedTimeStamp
     */
    private static function convertToYotiSignedTimestamp(ProtobufAnchor $anchor)
    {
        $signedTimeStamp = new \Yoti\Protobuf\Compubapi\SignedTimestamp();
        $signedTimeStamp->mergeFromString($anchor->getSignedTimeStamp());

        $timestamp = $signedTimeStamp->getTimestamp() / 1000000;
        $timeIncMicroSeconds = number_format($timestamp, 6, '.', '');
        // Format DateTime to include microseconds and timezone
        $dateTime = \DateTime::createFromFormat(
            'U.u',
            $timeIncMicroSeconds,
            new \DateTimeZone('UTC')
        );

        $yotiSignedTimeStamp = new \Yoti\Entity\SignedTimeStamp(
            $signedTimeStamp->getVersion(),
            $dateTime
        );

        return $yotiSignedTimeStamp;
    }

    /**
     * @param Traversable $certificateList
     *
     * @return array
     */
    private static function convertCertsListToX509(Traversable $certificateList)
    {
        $certsList = [];
        foreach ($certificateList as $certificate) {
            if ($X509CertObj = self::convertCertToX509($certificate)) {
                $certsList[] = $X509CertObj;
            }
        }
        return $certsList;
    }

    /**
     * Return X509 Cert Object.
     *
     * @param $certificate
     *
     * @return \stdClass
     */
    private static function convertCertToX509($certificate)
    {
        $X509 = new X509();
        $X509Data = $X509->loadX509($certificate);
        $decodedX509Data = json_decode(json_encode($X509Data), false);

        // Ensure serial number is cast to string.
        // @see \phpseclib\Math\BigInteger::__toString()
        $decodedX509Data
            ->tbsCertificate
            ->serialNumber
            ->value = (string) $X509Data['tbsCertificate']['serialNumber'];

        return $decodedX509Data;
    }

    /**
     * Get anchor type by OID.
     *
     * @param string $oid
     *
     * @return string
     */
    private static function getAnchorTypeByOid($oid)
    {
        $anchorTypesMap = self::getAnchorTypesMap();
        return isset($anchorTypesMap[$oid]) ? $anchorTypesMap[$oid] : Anchor::TYPE_UNKNOWN_NAME;
    }

    /**
     * @return array
     */
    private static function getAnchorTypesMap()
    {
        return [
            Anchor::TYPE_SOURCE_OID => Anchor::TYPE_SOURCE_NAME,
            Anchor::TYPE_VERIFIER_OID => Anchor::TYPE_VERIFIER_NAME,
        ];
    }
}
