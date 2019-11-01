<?php

namespace Yoti\Util\ExtraData;

use Yoti\Entity\AttributeDefinition;
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
    const DATE_FORMAT_RFC3339 = 'Y-m-d\TH:i:s.uP';

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
            $parsedDateTime = \DateTime::createFromFormat(
                self::DATE_FORMAT_RFC3339,
                $issuingAttributesProto->getExpiryDate(),
                new \DateTimeZone("UTC")
            );

            if ($parsedDateTime !== false) {
                $expiryDate = $parsedDateTime;
            } else {
                error_log("Failed to parse expiry date from ThirdPartyAttribute", 0);
            }

            $issuingAttributes = array_map(
                function ($definition) {
                    return new AttributeDefinition($definition->getName());
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
