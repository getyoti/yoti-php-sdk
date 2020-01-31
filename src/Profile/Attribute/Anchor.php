<?php

declare(strict_types=1);

namespace Yoti\Profile\Attribute;

/**
 * A class to represent a Yoti anchor. Anchors are metadata associated
 * to the attribute, which describe how an attribute has been provided
 * to Yoti (SOURCE Anchor) and how it has been verified (VERIFIER Anchor).
 *
 * If an attribute has only one SOURCE Anchor with the value set to
 * "USER_PROVIDED" and zero VERIFIER Anchors, then the attribute
 * is a self-certified one.
 */
class Anchor
{
    public const TYPE_SOURCE_NAME = 'SOURCE';
    public const TYPE_VERIFIER_NAME = 'VERIFIER';
    public const TYPE_UNKNOWN_NAME = 'UNKNOWN';
    public const TYPE_SOURCE_OID = '1.3.6.1.4.1.47127.1.1.1';
    public const TYPE_VERIFIER_OID = '1.3.6.1.4.1.47127.1.1.2';

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $subType;

    /**
     * @var \Yoti\Profile\Attribute\SignedTimeStamp
     */
    private $signedTimeStamp;

    /**
     * @var \stdClass[]
     */
    private $originServerCerts;

    /**
     * @param string $value
     * @param string $type
     * @param string $subType
     * @param \Yoti\Profile\Attribute\SignedTimeStamp $signedTimeStamp
     * @param \stdClass[] $originServerCerts
     */
    public function __construct(
        string $value,
        string $type,
        string $subType,
        SignedTimeStamp $signedTimeStamp,
        array $originServerCerts
    ) {
        $this->value = $value;
        $this->type = $type;
        $this->subType = $subType;
        $this->signedTimeStamp = $signedTimeStamp;
        $this->originServerCerts = $originServerCerts;
    }

    /**
     * Gets the value of the given anchor.
     *
     * Among possible options for SOURCE are "USER_PROVIDED", "PASSPORT",
     * "DRIVING_LICENCE", "NATIONAL_ID" and "PASSCARD".
     *
     * Among possible options for VERIFIER are "YOTI_ADMIN", "YOTI_IDENTITY",
     * "YOTI_OTP", "PASSPORT_NFC_SIGNATURE", "ISSUING_AUTHORITY" and
     * "ISSUING_AUTHORITY_PKI".
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Gets the type of the given anchor.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * SubType is an indicator of any specific processing method, or subcategory,
     * pertaining to an artifact.
     *
     * Examples:
     * - For a passport, this would be either "NFC" or "OCR".
     * - For a national ID, this could be "AADHAAR".
     *
     * @return string
     */
    public function getSubtype(): string
    {
        return $this->subType;
    }

    /**
     * Timestamp applied at the time of Anchor creation.
     *
     * @return \Yoti\Profile\Attribute\SignedTimeStamp
     */
    public function getSignedTimeStamp(): SignedTimeStamp
    {
        return $this->signedTimeStamp;
    }

    /**
     * Certificate chain generated when this Anchor was created (attribute value was
     * sourced or verified). Securely encodes the Anchor type and value.
     *
     * @return \stdClass[] of X509 certs
     */
    public function getOriginServerCerts(): array
    {
        return  $this->originServerCerts;
    }
}
