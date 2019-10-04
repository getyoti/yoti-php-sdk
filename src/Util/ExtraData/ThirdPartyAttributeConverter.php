<?php

namespace Yoti\Util\ExtraData;

use Yoti\Entity\AttributeDefinition;
use Yoti\Entity\AttributeIssuanceDetails;
use Yoti\Exception\ExtraDataException;
use Sharepubapi\IssuingAttributes;
use Sharepubapi\ThirdPartyAttribute as ThirdPartyAttributeProto;
use Yoti\Util\DateTime;

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
            try {
                $expiryDate = DateTime::stringToDateTime($issuingAttributesProto->getExpiryDate());
            } catch (\Exception $e) {
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
