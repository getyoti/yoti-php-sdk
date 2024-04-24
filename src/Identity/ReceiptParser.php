<?php

namespace Yoti\Identity;

use Psr\Log\LoggerInterface;
use Yoti\Exception\EncryptedDataException;
use Yoti\Profile\ApplicationProfile;
use Yoti\Profile\ExtraData;
use Yoti\Profile\UserProfile;
use Yoti\Profile\Util\Attribute\AttributeListConverter;
use Yoti\Profile\Util\ExtraData\ExtraDataConverter;
use Yoti\Identity\Util\IdentityEncryptedData;
use Yoti\Protobuf\Attrpubapi\AttributeList;
use Yoti\Util\Logger;
use Yoti\Util\PemFile;

class ReceiptParser
{
    /**
     * @var LoggerInterface|Logger
     */
    private $logger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger ?? new Logger();
    }

    public function createSuccess(
        WrappedReceipt $wrappedReceipt,
        ReceiptItemKey $wrappedItemKey,
        PemFile $pemFile
    ): Receipt {
        $receiptKey = $this->decryptReceiptKey($wrappedReceipt->getWrappedKey(), $wrappedItemKey, $pemFile);

        $applicationProfile = new ApplicationProfile(
            AttributeListConverter::convertToYotiAttributesList($this->parseProfileAttr(
                $wrappedReceipt->getProfile(),
                $receiptKey,
            ))
        );

        $extraData = null !== $wrappedReceipt->getExtraData() ?
            $this->parseExtraData($wrappedReceipt->getExtraData(), $receiptKey) :
            null;

        $userProfile = null !== $wrappedReceipt->getOtherPartyProfile() ? new UserProfile(
            AttributeListConverter::convertToYotiAttributesList(
                $this->parseProfileAttr(
                    $wrappedReceipt->getOtherPartyProfile(),
                    $receiptKey,
                )
            )
        ) : null;

        $otherExtraData = null !== $wrappedReceipt->getOtherPartyExtraData() ?
            $this->parseExtraData($wrappedReceipt->getOtherPartyExtraData(), $receiptKey) :
            null;


        $receipt = (new ReceiptBuilder())
            ->withId($wrappedReceipt->getId())
            ->withSessionId($wrappedReceipt->getSessionId())
            ->withTimestamp($wrappedReceipt->getTimestamp())
            ->withApplicationContent(
                $applicationProfile,
                $extraData
            )
            ->withUserContent(
                $userProfile,
                $otherExtraData
            );

        if (null !== $wrappedReceipt->getRememberMeId()) {
            $receipt->withRememberMeId($wrappedReceipt->getRememberMeId());
        }

        if (null !== $wrappedReceipt->getParentRememberMeId()) {
            $receipt->withParentRememberMeId($wrappedReceipt->getParentRememberMeId());
        }

        return $receipt->build();
    }

    public function createFailure(WrappedReceipt $wrappedReceipt): Receipt
    {
        return (new ReceiptBuilder())
            ->withId($wrappedReceipt->getId())
            ->withSessionId($wrappedReceipt->getSessionId())
            ->withTimestamp($wrappedReceipt->getTimestamp())
            ->withError($wrappedReceipt->getError())
            ->build();
    }

    private function decryptReceiptKey(string $wrappedKey, ReceiptItemKey $wrappedItemKey, PemFile $pemFile): string
    {
        // Convert 'iv' and 'value' from base64 to binary
        $iv = base64_decode($wrappedItemKey->getIv(), true);
        $encryptedItemKey = base64_decode($wrappedItemKey->getValue(), true);

        // Decrypt the 'value' field (encrypted item key) using the private key
        $unwrappedKey = '';
        if (!openssl_private_decrypt(
            $encryptedItemKey,
            $unwrappedKey,
            (string)$pemFile
        )) {
            throw new EncryptedDataException('Could not decrypt the item key');
        }

        // Check that 'wrappedKey' is a base64-encoded string
        $wrappedKey = base64_decode($wrappedKey, true);
        if ($wrappedKey === false) {
            throw new EncryptedDataException('wrappedKey is not a valid base64-encoded string');
        }

        // Decompose the 'wrappedKey' into 'cipherText' and 'tag'
        $cipherText = substr($wrappedKey, 0, -16);
        $tag = substr($wrappedKey, -16);

        // Decrypt the 'cipherText' using the 'iv' and the decrypted item key
        $receiptKey = openssl_decrypt(
            $cipherText,
            'aes-256-gcm',
            $unwrappedKey,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );
        if ($receiptKey === false) {
            throw new EncryptedDataException('Could not decrypt the receipt key');
        }

        return $receiptKey;
    }

    private function parseProfileAttr(string $profile, string $wrappedKey): AttributeList
    {
        $attributeList = new AttributeList();

        $decryptedData = IdentityEncryptedData::decrypt(
            $profile,
            $wrappedKey
        );

        $attributeList->mergeFromString($decryptedData);

        return $attributeList;
    }

    private function parseExtraData(string $extraData, string $wrappedKey): ExtraData
    {
        $decryptAttribute = IdentityEncryptedData::decrypt(
            $extraData,
            $wrappedKey
        );

        return ExtraDataConverter::convertValue(
            $decryptAttribute,
            $this->logger
        );
    }
}
