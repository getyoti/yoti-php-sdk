<?php

namespace Yoti\Entity;

use Compubapi\EncryptedData as EncryptedDataProto;

class EncryptedData
{
    /**
     * @var \Compubapi\EncryptedData
     */
    private $encryptedDataProto;

    /**
     * @var string
     */
    private $pem;

    /**
     * @var string
     */
    private $wrappedKey;

    /**
     * @param \Compubapi\EncryptedData $encryptedDataProto
     */
    private function __construct(EncryptedDataProto $encryptedDataProto)
    {
        $this->encryptedDataProto = $encryptedDataProto;
    }

    /**
     * Creates Encrypted Data object from string.
     *
     * @param string $data
     *   Base64 encoded data.
     *
     * @return Yoti\Entity\EncryptedData
     */
    public static function fromString($data)
    {
        $encryptedDataProto = new \Compubapi\EncryptedData();
        $encryptedDataProto->mergeFromString(base64_decode($data));
        return new static($encryptedDataProto);
    }

    /**
     * Creates Encrypted Data object from \Compubapi\EncryptedData.
     *
     * @param \Compubapi\EncryptedData $encryptedDataProto
     *
     * @return Yoti\Entity\EncryptedData
     */
    public static function fromEncryptedDataProto(EncryptedDataProto $encryptedDataProto)
    {
        return new static($encryptedDataProto);
    }

    /**
     * @param string $pem
     *
     * @return \Yoti\Entity\EncryptedData
     */
    public function withPem($pem)
    {
        $this->pem = $pem;
        return $this;
    }

    /**
     * @param string $wrappedKey
     *
     * @return \Yoti\Entity\EncryptedData
     */
    public function withWrappedKey($wrappedKey)
    {
        $this->wrappedKey = $wrappedKey;
        return $this;
    }

    /**
     * @return string
     */
    private function getPem()
    {
        if (isset($this->pem)) {
            return $this->pem;
        }
        throw new \LogicException('Pem string must be provided');
    }

    /**
     * @return string
     */
    private function getWrappedKey()
    {
        if (isset($this->wrappedKey)) {
            return $this->wrappedKey;
        }
        throw new \LogicException('Wrapped key must be provided');
    }

    /**
     * @return string
     */
    public function decrypt()
    {
        openssl_private_decrypt(
            base64_decode($this->getWrappedKey()),
            $unwrappedKey,
            $this->getPem()
        );

        return openssl_decrypt(
            $this->encryptedDataProto->getCipherText(),
            'aes-256-cbc',
            $unwrappedKey,
            OPENSSL_RAW_DATA,
            $this->encryptedDataProto->getIv()
        );
    }
}
