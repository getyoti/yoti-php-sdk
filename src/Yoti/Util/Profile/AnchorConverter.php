<?php

namespace Yoti\Util\Profile;

use Traversable;
use phpseclib\File\ASN1;
use phpseclib\File\X509;
use Attrpubapi\Anchor;
use Yoti\Entity\Anchor as YotiAnchor;

class AnchorConverter
{
    /**
     * Convert Protobuf Anchor to a map of oid -> Yoti Anchor
     *
     * @param Anchor $anchor
     *
     * @return array|null
     */
    public static function convert(Anchor $protobufAnchor)
    {
        $anchorMap = NULL;
        $ASN1 = new ASN1();
        $X509 = new X509();
        $anchorSubType = $protobufAnchor->getSubType();
        $yotiSignedTimeStamp = self::convertToYotiSignedTimestamp($protobufAnchor);
        $X509CertsList = self::convertCertsListToX509($X509, $protobufAnchor->getOriginServerCerts());
        $anchorTypesMap = self::getAnchorTypesMap();

        foreach ($X509CertsList as $certX509Obj) {
            $certExtsArr = $certX509Obj->tbsCertificate->extensions;

            foreach($anchorTypesMap as $oid => $anchorType) {
                foreach($certExtsArr as $extObj) {
                    $extArr = (array) $extObj;
                    $oidFound = array_search($oid, $extArr, TRUE);
                    if ($oidFound !== FALSE && is_string($extArr['extnValue'])) {
                        $extEncodedValue = $extArr['extnValue'];

                        if ($decodedAnchorValue = self::decodeAnchorValue($ASN1, $X509, $extEncodedValue)) {
                            $yotiAnchor = self::createYotiAnchor(
                                $decodedAnchorValue,
                                $anchorType,
                                $anchorSubType,
                                $yotiSignedTimeStamp,
                                $X509CertsList
                            );
                            $anchorMap = [
                                'oid' => $oid,
                                'yoti_anchor' => $yotiAnchor
                            ];
                            // We are only looking for one YotiAnchor from protobufAnchor
                            return $anchorMap;
                        }
                    }
                }
            }
        }
        return $anchorMap;
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
     * @param ASN1 $ASN1
     * @param X509 $X509
     * @param $extEncodedValue
     *
     * @return null|string
     */
    private static function decodeAnchorValue(ASN1 $ASN1, X509 $X509, $extEncodedValue)
    {
        $encodedBER = $X509->_extractBER($extEncodedValue);
        $decodedValArr = $ASN1->decodeBER($encodedBER);
        if (isset($decodedValArr[0]['content'][0]['content'])) {
            return $decodedValArr[0]['content'][0]['content'];
        }
        return NULL;
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
     * @param X509 $X509
     * @param Traversable $certificateList
     *
     * @return array
     */
    private static function convertCertsListToX509(X509 $X509, Traversable $certificateList) {
        $certsList = [];
        foreach($certificateList as $certificate) {
            if ($X509CertObj = self::convertCertToX509($X509, $certificate)) {
                $certsList[] = $X509CertObj;
            }
        }
        return $certsList;
    }

    /**
     * Return X509 Cert Object.
     *
     * @param X509 $X509
     * @param $certificate
     *
     * @return \stdClass
     */
    private static function convertCertToX509(X509 $X509, $certificate) {
        $X509Data = $X509->loadX509($certificate);
        return json_decode(json_encode($X509Data), FALSE);
    }

    /**
     * @param string $oid
     *
     * @return string
     */
    private static function getAnchorTypeByOid($oid)
    {
        $anchorTypesMap = self::getAnchorTypesMap();
        return isset($anchorTypesMap[$oid]) ? $anchorTypesMap[$oid] : 'Unknown';
    }

    /**
     * @return array
     */
    private static function getAnchorTypesMap()
    {
        return [
            YotiAnchor::TYPE_SOURCE_OID => YotiAnchor::TYPE_SOURCE_NAME,
            YotiAnchor::TYPE_VERIFIER_OID => YotiAnchor::TYPE_VERIFIER_NAME,
        ];
    }
}