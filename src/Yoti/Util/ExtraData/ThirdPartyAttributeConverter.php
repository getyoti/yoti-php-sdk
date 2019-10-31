<?php

namespace Yoti\Util\ExtraData;

use Yoti\Entity\AttributeIssuanceDetails;
use Yoti\Exception\ExtraDataException;
use Yoti\Sharepubapi\IssuingAttributes;
use Yoti\Sharepubapi\ThirdPartyAttribute as ThirdPartyAttributeProto;

class ThirdPartyAttributeConverter
{
    /**
     * RFC3339 format used by third party attributes.
     *
     * This will be replaced by \DateTime::RFC3339_EXTENDED
     * once PHP 5.6 is no longer supported.
     */
    const DATE_FORMAT_RFC3339 = 'Y-m-d\TH:i:s.vP';

    /**
     * @param string $value
     *
     * @return \Yoti\Entity\AttributeIssuanceDetails
     */
    public static function convertValue($value)
    {
        $thirdPartyAttributeProto = new ThirdPartyAttributeProto();
        $thirdPartyAttributeProto->mergeFromString($value);

        $token = $thirdPartyAttributeProto->getIssuanceToken();
        if (empty($token)) {
            throw new ExtraDataException('Failed to retrieve token from ThirdPartyAttribute');
        }

        $expiryDate = null;
        $issuingAttributes = [];

        $issuingAttributesProto = $thirdPartyAttributeProto->getIssuingAttributes();

        if ($issuingAttributesProto instanceof IssuingAttributes) {
            $expiryDateString = $issuingAttributesProto->getExpiryDate();
            $parsedExpiryDate = \DateTime::createFromFormat(
                self::DATE_FORMAT_RFC3339,
                $expiryDateString,
                new \DateTimeZone("UTC")
            );
            if ($parsedExpiryDate !== false) {
                $expiryDate = $parsedExpiryDate;
            } else {
                error_log(sprintf(
                    "Failed to parse expiry date '%s' from ThirdPartyAttribute using format '%s'",
                    $expiryDateString,
                    self::DATE_FORMAT_RFC3339
                ), 0);
            }

            $issuingAttributes = array_map(
                function ($definition) {
                    return $definition->getName();
                },
                iterator_to_array($issuingAttributesProto->getDefinitions())
            );
        }

        return new AttributeIssuanceDetails(
            $token,
            $expiryDate,
            $issuingAttributes
        );
    }
}
