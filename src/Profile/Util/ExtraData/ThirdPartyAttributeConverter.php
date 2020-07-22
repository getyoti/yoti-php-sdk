<?php

declare(strict_types=1);

namespace Yoti\Profile\Util\ExtraData;

use Yoti\Exception\ExtraDataException;
use Yoti\Profile\ExtraData\AttributeDefinition;
use Yoti\Profile\ExtraData\AttributeIssuanceDetails;
use Yoti\Protobuf\Sharepubapi\IssuingAttributes;
use Yoti\Protobuf\Sharepubapi\ThirdPartyAttribute;
use Yoti\Util\Base64;
use Yoti\Util\DateTime;

class ThirdPartyAttributeConverter
{
    /**
     * @param string $value
     *
     * @return \Yoti\Profile\ExtraData\AttributeIssuanceDetails
     */
    public static function convertValue(string $value): AttributeIssuanceDetails
    {
        $thirdPartyAttributeProto = new ThirdPartyAttribute();
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
                function ($definition): AttributeDefinition {
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
    private static function parseToken(string $token): string
    {
        if (strlen($token) === 0) {
            throw new ExtraDataException('Failed to retrieve token from ThirdPartyAttribute');
        }

        return Base64::urlEncode($token);
    }
}
