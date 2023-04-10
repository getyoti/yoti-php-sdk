<?php

namespace Yoti\Identity;

use Yoti\Exception\EncryptedDataException;
use Yoti\Protobuf\Compubapi\EncryptedData;

class ReceiptItemKey
{
    private string $id;

    private string $iv;

    private string $value;

    /**
     * @param array<string, mixed> $sessionData
     */
    public function __construct(array $sessionData)
    {
        $this->id = $sessionData['id'];
        $this->setIv($sessionData['iv']);

        $decoded = base64_decode($sessionData['value'], true);
        if ($decoded === false) {
            throw new EncryptedDataException('Could not decode data');
        }
        $this->value = $decoded;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIv(): string
    {
        return $this->iv;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function setIv(string $iv): void
    {
        $decodedProto = base64_decode($iv, true);
        if ($decodedProto === false) {
            throw new EncryptedDataException('Could not decode data');
        }
        $encryptedDataProto = new EncryptedData();
        $encryptedDataProto->mergeFromString($decodedProto);

        $this->iv = $encryptedDataProto->getIv();
    }
}
