<?php

declare(strict_types=1);

namespace Yoti\Profile\Util\Attribute;

use phpseclib3\File\ASN1;
use phpseclib3\File\X509;
use Yoti\Profile\Attribute\Anchor;
use Yoti\Profile\Attribute\SignedTimeStamp;
use Yoti\Protobuf\Attrpubapi\Anchor as ProtobufAnchor;
use Yoti\Util\DateTime;
use Yoti\Util\Json;

class AnchorConverter
{
    /**
     * Convert Protobuf Anchor to Yoti Anchor
     *
     * @param \Yoti\Protobuf\Attrpubapi\Anchor $protobufAnchor
     *
     * @return \Yoti\Profile\Attribute\Anchor
     */
    public static function convert(ProtobufAnchor $protobufAnchor): Anchor
    {
        $anchorSubType = $protobufAnchor->getSubType();
        $yotiSignedTimeStamp = self::convertToYotiSignedTimestamp($protobufAnchor);
        $X509CertsList = self::convertCertsListToX509($protobufAnchor->getOriginServerCerts());

        foreach ($X509CertsList as $certX509Obj) {
            foreach ($certX509Obj->tbsCertificate->extensions as $extObj) {
                $anchorType = self::getAnchorTypeByOid($extObj->extnId);
                if ($anchorType !== Anchor::TYPE_UNKNOWN_NAME) {
                    return new Anchor(
                        self::decodeAnchorValue($extObj->extnValue),
                        $anchorType,
                        $anchorSubType,
                        $yotiSignedTimeStamp,
                        $X509CertsList
                    );
                }
            }
        }

        return new Anchor(
            '',
            Anchor::TYPE_UNKNOWN_NAME,
            $anchorSubType,
            $yotiSignedTimeStamp,
            $X509CertsList
        );
    }

    /**
     * @param string $extEncodedValue
     *
     * @return string
     */
    private static function decodeAnchorValue(string $extEncodedValue): string
    {
        $encodedBER = ASN1::extractBER($extEncodedValue);
        $decodedValArr = ASN1::decodeBER($encodedBER);

        if (isset($decodedValArr[0]['content'][0]['content'])) {
            $value = $decodedValArr[0]['content'][0]['content'];

            if (!is_string($value)) {
                return '';
            }

            $detectionOrder = mb_detect_order();
            $encoding = mb_detect_encoding($value, is_array($detectionOrder) ? $detectionOrder : null, true);

            if (is_string($encoding)) {
                if ($encoding !== 'UTF-8') {
                    // PHPStan implies $value is string, $encoding is valid string, so result is string.
                    return mb_convert_encoding($value, 'UTF-8', $encoding);
                }
                // It is UTF-8
                return $value;
            } else { // $encoding is false (detection failed)
                if (!mb_check_encoding($value, 'UTF-8')) {
                    // PHPStan implies $value is string, so result is string.
                    return mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
                }
                return $value; // It's valid UTF-8 despite detection failing
            }
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

        return new SignedTimeStamp(
            $signedTimeStamp->getVersion(),
            DateTime::timestampToDateTime((int) $signedTimeStamp->getTimestamp())
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

        array_walk_recursive($X509Data, function (&$item): void {
            if (is_string($item)) {
                $detectionOrder = mb_detect_order();
                $encoding = mb_detect_encoding($item, is_array($detectionOrder) ? $detectionOrder : null, true);

                if (is_string($encoding)) {
                    if ($encoding !== 'UTF-8' && $encoding !== 'ASCII') {
                        // PHPStan implies $item is string, $encoding is valid string, so result is string.
                        // The 'else' branch for base64_encode was deemed unreachable by PHPStan.
                        $item = mb_convert_encoding($item, 'UTF-8', $encoding);
                    }
                    // If $encoding is 'UTF-8' or 'ASCII', $item is left as is.
                } else { // $encoding is false (detection failed)
                    if (!mb_check_encoding($item, 'UTF-8') && !mb_check_encoding($item, 'ASCII')) {
                        $item = base64_encode($item);
                    }
                    // If it's valid UTF-8/ASCII despite detection failing, $item is left as is.
                }
            }
        });

        $decodedX509Data = Json::decode(Json::encode(Json::convertFromLatin1ToUtf8Recursively($X509Data)), false);
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
