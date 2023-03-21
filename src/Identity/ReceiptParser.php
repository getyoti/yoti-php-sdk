<?php

namespace Yoti\Identity;

use Psr\Log\LoggerInterface;
use Yoti\Exception\EncryptedDataException;
use Yoti\Profile\ApplicationProfile;
use Yoti\Profile\ExtraData;
use Yoti\Profile\UserProfile;
use Yoti\Profile\Util\Attribute\AttributeListConverter;
use Yoti\Profile\Util\EncryptedData;
use Yoti\Profile\Util\ExtraData\ExtraDataConverter;
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
                $pemFile
            ))
        );

        $extraData = null !== $wrappedReceipt->getExtraData() ?
            $this->parseExtraData($wrappedReceipt->getExtraData(), $receiptKey, $pemFile) :
            null;

        $userProfile = null !== $wrappedReceipt->getOtherPartyProfile() ? new UserProfile(
            AttributeListConverter::convertToYotiAttributesList(
                $this->parseProfileAttr(
                    $wrappedReceipt->getOtherPartyProfile(),
                    $receiptKey,
                    $pemFile
                )
            )
        ) : null;

        $otherExtraData = null !== $wrappedReceipt->getOtherPartyExtraData() ?
            $this->parseExtraData($wrappedReceipt->getOtherPartyExtraData(), $receiptKey, $pemFile) :
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
        openssl_private_decrypt(
            $wrappedItemKey->getValue(),
            $unwrappedKey,
            (string)$pemFile
        );

        $receiptKey = openssl_decrypt(
            $wrappedKey,
            'aes-256-gcm',
            $unwrappedKey,
            OPENSSL_RAW_DATA,
            $wrappedItemKey->getIv()
        );
        if ($receiptKey === false) {
            throw new EncryptedDataException('Could not decrypt data');
        }

        return $receiptKey;
    }

    private function parseProfileAttr(string $profile, string $wrappedKey, PemFile $pemFile): AttributeList
    {
        $attributeList = new AttributeList();

        $decryptedData = EncryptedData::decrypt(
            $profile,
            $wrappedKey,
            $pemFile
        );

        $attributeList->mergeFromString($decryptedData);

        return $attributeList;
    }

    private function parseExtraData(string $extraData, string $wrappedKey, PemFile $pemFile): ExtraData
    {
        $decryptAttribute = EncryptedData::decrypt(
            $extraData,
            $wrappedKey,
            $pemFile
        );

        return ExtraDataConverter::convertValue(
            $decryptAttribute,
            $this->logger
        );
    }
}
