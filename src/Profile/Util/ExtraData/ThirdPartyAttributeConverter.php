<?php

declare(strict_types=1);

namespace Yoti\Profile\Util\ExtraData;

use Psr\Log\LoggerInterface;
use Yoti\Exception\ExtraDataException;
use Yoti\Profile\ExtraData\AttributeDefinition;
use Yoti\Profile\ExtraData\AttributeIssuanceDetails;
use Yoti\Protobuf\Sharepubapi\IssuingAttributes;
use Yoti\Protobuf\Sharepubapi\ThirdPartyAttribute;
use Yoti\Util\DateTime;
use Yoti\Util\Logger;

class ThirdPartyAttributeConverter
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $value
     *
     * @return \Yoti\Profile\ExtraData\AttributeIssuanceDetails
     */
    public function convert(string $value): AttributeIssuanceDetails
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
                $this->logger->warning(
                    'Failed to parse expiry date from ThirdPartyAttribute',
                    ['exception' => $e]
                );
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
     * @deprecated replaced by ThirdPartyAttributeConverter::convert()
     *
     * @param string $value
     *
     * @return \Yoti\Profile\ExtraData\AttributeIssuanceDetails
     */
    public static function convertValue(string $value): AttributeIssuanceDetails
    {
        return (new self(new Logger()))->convert($value);
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

        return base64_encode($token);
    }
}
