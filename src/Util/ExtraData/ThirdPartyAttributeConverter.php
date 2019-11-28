<?php

namespace Yoti\Util\ExtraData;

use Yoti\Entity\AttributeDefinition;
use Yoti\Entity\AttributeIssuanceDetails;
use Yoti\Exception\ExtraDataException;
use Yoti\Util\Constants;
use Yoti\Protobuf\Sharepubapi\IssuingAttributes;
use Yoti\Protobuf\Sharepubapi\ThirdPartyAttribute as ThirdPartyAttributeProto;

class ThirdPartyAttributeConverter
{
    /**
     * @param string $value
     *
     * @return \Yoti\Entity\AttributeIssuanceDetails
     */
    public static function convertValue($value)
    {
        $thirdPartyAttributeProto = new ThirdPartyAttributeProto();
        $thirdPartyAttributeProto->mergeFromString($value);

        $token = self::parseToken($thirdPartyAttributeProto->getIssuanceToken());
        $expiryDate = null;
        $issuingAttributes = [];

        $issuingAttributesProto = $thirdPartyAttributeProto->getIssuingAttributes();

        if ($issuingAttributesProto instanceof IssuingAttributes) {
            $parsedDateTime = \DateTime::createFromFormat(
                Constants::DATE_FORMAT_RFC3339,
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

    /**
     * @param string $token
     *   Tokens bytes.
     *
     * @return string
     *   Base64 encoded token.
     *
     * @throws \Yoti\Exception\ExtraDataException
     */
    private static function parseToken($token)
    {
        if (empty($token)) {
            throw new ExtraDataException('Failed to retrieve token from ThirdPartyAttribute');
        }
        return base64_encode($token);
    }
}
