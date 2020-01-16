<?php

declare(strict_types=1);

namespace Yoti\Profile\Util\Attribute;

use phpseclib\File\ASN1;
use phpseclib\File\X509;
use Yoti\Exception\AttributeException;
use Yoti\Profile\Attribute\Anchor;
use Yoti\Profile\Attribute\SignedTimeStamp;
use Yoti\Protobuf\Attrpubapi\Anchor as ProtobufAnchor;
use Yoti\Util\Json;

class AnchorConverter
{
    /**
     * Convert Protobuf Anchor to a map of oid -> Yoti Anchor
     *
     * @param \Yoti\Protobuf\Attrpubapi\Anchor $protobufAnchor
     *
     * @return array<string, mixed>
     */
    public static function convert(ProtobufAnchor $protobufAnchor): array
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
     * @param string $extEncodedValue
     *
     * @return string
     */
    private static function decodeAnchorValue(string $extEncodedValue): string
    {
        $X509 = new X509();
        $ASN1 = new ASN1();
        $encodedBER = $X509->_extractBER($extEncodedValue);
        $decodedValArr = $ASN1->decodeBER($encodedBER);
        if (isset($decodedValArr[0]['content'][0]['content'])) {
            return $decodedValArr[0]['content'][0]['content'];
        }
        return '';
    }

    /**
     * @param \Yoti\Protobuf\Attrpubapi\Anchor $anchor
     *
     * @return \Yoti\Profile\Attribute\SignedTimeStamp
     */
    private static function convertToYotiSignedTimestamp(ProtobufAnchor $anchor): SignedTimeStamp
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

        if ($dateTime === false) {
            throw new AttributeException('Could not parse anchor timestamp');
        }

        return new SignedTimeStamp(
            $signedTimeStamp->getVersion(),
            $dateTime
        );
    }

    /**
     * @param \Traversable<string> $certificateList
     *
     * @return \stdClass[]
     */
    private static function convertCertsListToX509(\Traversable $certificateList): array
    {
        $certsList = [];
        foreach ($certificateList as $certificate) {
            $certsList[] = self::convertCertToX509($certificate);
        }
        return $certsList;
    }

    /**
     * Return X509 Cert Object.
     *
     * @param string $certificate
     *
     * @return \stdClass
     */
    private static function convertCertToX509(string $certificate): \stdClass
    {
        $X509 = new X509();
        $X509Data = $X509->loadX509($certificate);
        $decodedX509Data = Json::decode(Json::encode($X509Data), false);

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
    private static function getAnchorTypeByOid(string $oid): string
    {
        $anchorTypesMap = self::getAnchorTypesMap();
        return isset($anchorTypesMap[$oid]) ? $anchorTypesMap[$oid] : Anchor::TYPE_UNKNOWN_NAME;
    }

    /**
     * @return array<string, string>
     */
    private static function getAnchorTypesMap(): array
    {
        return [
            Anchor::TYPE_SOURCE_OID => Anchor::TYPE_SOURCE_NAME,
            Anchor::TYPE_VERIFIER_OID => Anchor::TYPE_VERIFIER_NAME,
        ];
    }
}
