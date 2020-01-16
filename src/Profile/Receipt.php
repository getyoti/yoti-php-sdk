<?php

declare(strict_types=1);

namespace Yoti\Profile;

use Yoti\Exception\ReceiptException;
use Yoti\Profile\ExtraData\ExtraData;
use Yoti\Profile\Util\EncryptedData;
use Yoti\Profile\Util\ExtraData\ExtraDataConverter;
use Yoti\Protobuf\Attrpubapi\AttributeList;
use Yoti\Util\PemFile;

class Receipt
{
    const ATTR_TIMESTAMP = 'timestamp';
    const ATTR_RECEIPT_ID = 'receipt_id';
    const ATTR_REMEMBER_ME_ID = 'remember_me_id';
    const ATTR_PARENT_REMEMBER_ME_ID = 'parent_remember_me_id';
    const ATTR_PROFILE_CONTENT = 'profile_content';
    const ATTR_SHARING_OUT_COME = 'sharing_outcome';
    const ATTR_WRAPPED_RECEIPT_KEY = 'wrapped_receipt_key';
    const ATTR_OTHER_PARTY_PROFILE_CONTENT = 'other_party_profile_content';
    const ATTR_EXTRA_DATA_CONTENT = 'extra_data_content';

    /**
     * @var array
     */
    private $receiptData;

    /**
     * Receipt constructor.
     *
     * @param array $receiptData
     *
     * @throws ReceiptException
     */
    public function __construct(array $receiptData)
    {
        $this->validateReceipt($receiptData);

        $this->receiptData = $receiptData;
    }

    public function getReceiptId(): string
    {
        return $this->getAttribute(self::ATTR_RECEIPT_ID);
    }

    public function getRememberMeId(): ?string
    {
        return $this->getAttribute(self::ATTR_REMEMBER_ME_ID);
    }

    public function getParentRememberMeId(): ?string
    {
        return $this->getAttribute(self::ATTR_PARENT_REMEMBER_ME_ID);
    }

    public function getSharingOutcome(): string
    {
        return $this->getAttribute(self::ATTR_SHARING_OUT_COME);
    }

    public function getWrappedReceiptKey(): string
    {
        return $this->getAttribute(self::ATTR_WRAPPED_RECEIPT_KEY);
    }

    public function getTimestamp(): string
    {
        return $this->getAttribute(self::ATTR_TIMESTAMP);
    }

    public function getAttribute(string $attributeName)
    {
        if (!empty($attributeName) && isset($this->receiptData[$attributeName])) {
            return $this->receiptData[$attributeName];
        }
        return null;
    }

    /**
     * Return Protobuf Attributes List.
     *
     * @param string $attributeName
     * @param \Yoti\Util\PemFile $pem
     *
     * @return \Yoti\Protobuf\Attrpubapi\AttributeList
     */
    public function parseAttribute(string $attributeName, PemFile $pemFile): AttributeList
    {
        $attributeList = new AttributeList();
        $attributeList->mergeFromString(
            $this->decryptAttribute($attributeName, $pemFile)
        );

        return $attributeList;
    }

    /**
     * @param \Yoti\Util\PemFile $pem
     *
     * @return \Yoti\Profile\ExtraData\ExtraData
     */
    public function parseExtraData(PemFile $pemFile): ExtraData
    {
        return ExtraDataConverter::convertValue(
            $this->decryptAttribute(self::ATTR_EXTRA_DATA_CONTENT, $pemFile)
        );
    }

    /**
     * Decrypt receipt attribute.
     *
     * @param string $attributeName
     * @param string $pem
     *
     * @return string|null
     *
     * @throws \Yoti\Exception\EncryptedDataException
     */
    private function decryptAttribute(string $attributeName, PemFile $pemFile): ?string
    {
        $encryptedData = $this->getAttribute($attributeName);

        if ($encryptedData == null) {
            return null;
        }

        return EncryptedData::decrypt(
            $encryptedData,
            $this->getWrappedReceiptKey(),
            $pemFile
        );
    }

    /**
     * Check Wrapped_receipt_key exists and is not NULL.
     *
     * @throws ReceiptException
     */
    private function validateReceipt(array $receiptData): void
    {
        if (!isset($receiptData[self::ATTR_WRAPPED_RECEIPT_KEY])) {
            throw new ReceiptException('Wrapped Receipt key attr is missing');
        }
    }
}
