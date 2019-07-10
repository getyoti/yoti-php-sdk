<?php

namespace Yoti\Util\Profile;

use Traversable;
use phpseclib\File\ASN1;
use phpseclib\File\X509;
use Attrpubapi\Anchor;
use Yoti\Entity\Anchor as YotiAnchor;
use phpseclib\Math\BigInteger;

class AnchorConverter
{
    /**
     * Convert Protobuf Anchor to a map of oid -> Yoti Anchors
     *
     * @param Anchor $anchor
     *
     * @return array map of oid => YotiAnchor[]
     */
    public static function convertAnchor(Anchor $protobufAnchor)
    {
        $anchorMap = [];
        $anchorSubType = $protobufAnchor->getSubType();
        $yotiSignedTimeStamp = self::convertToYotiSignedTimestamp($protobufAnchor);
        $X509CertsList = self::convertCertsListToX509($protobufAnchor->getOriginServerCerts());

        foreach ($X509CertsList as $certX509Obj) {
            $certExtsArr = $certX509Obj->tbsCertificate->extensions;

            foreach ($certExtsArr as $extObj) {
                $anchorType = self::getAnchorTypeByOid($extObj->extnId);
                $anchorValue = '';
                if ($anchorType !== YotiAnchor::TYPE_UNKNOWN_NAME) {
                    $anchorValue = self::decodeAnchorValue($extObj->extnValue);
                }
                $yotiAnchor = self::createYotiAnchor(
                    $anchorValue,
                    $anchorType,
                    $anchorSubType,
                    $yotiSignedTimeStamp,
                    $X509CertsList
                );
                $mapKey = self::getAnchorTypeKey($anchorType);
                $anchorMap[$mapKey][] = $yotiAnchor;
            }
        }
        return $anchorMap;
    }

    /**
     * Convert Protobuf Anchor to a map of oid -> Yoti Anchor
     *
     * @deprecated no longer in use.
     *
     * @param Anchor $anchor
     *
     * @return array|null
     */
    public static function convert(Anchor $protobufAnchor)
    {
        $extensions = self::convertAnchor($protobufAnchor);
        foreach (array_keys(self::getAnchorTypesMap()) as $oid) {
            if (isset($extensions[$oid][0])) {
                return [
                    'oid' => $oid,
                    'yoti_anchor' => $extensions[$oid][0],
                ];
            }
        }
    }

    /**
     * @param string $value
     * @param string $type
     * @param string $subType
     * @param \Yoti\Entity\SignedTimestamp $signedTimestamp
     * @param array $X509CertsList
     *
     * @return YotiAnchor
     */
    private static function createYotiAnchor($value, $type, $subType, $signedTimestamp, $X509CertsList)
    {
        return  new YotiAnchor(
            $value,
            $type,
            $subType,
            $signedTimestamp,
            $X509CertsList
        );
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
     * @param \Attrpubapi\Anchor $anchor
     *
     * @return \Yoti\Entity\SignedTimeStamp
     */
    private static function convertToYotiSignedTimestamp(Anchor $anchor)
    {
        $signedTimeStamp = new \Compubapi\SignedTimestamp();
        $signedTimeStamp->mergeFromString($anchor->getSignedTimeStamp());

        $timestamp = $signedTimeStamp->getTimestamp()/1000000;
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
        return isset($anchorTypesMap[$oid]) ? $anchorTypesMap[$oid] : YotiAnchor::TYPE_UNKNOWN_NAME;
    }

    /**
     * Get anchor type key by type.
     *
     * @param string $type
     *
     * @return string
     */
    private static function getAnchorTypeKey($type)
    {
        $anchorTypesMap = array_flip(self::getAnchorTypesMap());
        return !empty($anchorTypesMap[$type]) ? $anchorTypesMap[$type] : YotiAnchor::TYPE_UNKNOWN_NAME;
    }

    /**
     * @return array
     */
    private static function getAnchorTypesMap()
    {
        return [
            YotiAnchor::TYPE_SOURCE_OID => YotiAnchor::TYPE_SOURCE_NAME,
            YotiAnchor::TYPE_VERIFIER_OID => YotiAnchor::TYPE_VERIFIER_NAME,
            YotiAnchor::TYPE_UNKNOWN_OID => YotiAnchor::TYPE_UNKNOWN_NAME,
        ];
    }
}
