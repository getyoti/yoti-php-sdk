<?php

namespace Yoti\Util;

use Yoti\Protobuf\Compubapi\EncryptedData as EncryptedDataProto;

class EncryptedData
{
    /**
     * @param string $data
     * @param string $wrappedKey
     * @param string $pem
     *
     * @return string
     */
    public static function decrypt($data, $wrappedKey, $pem)
    {
        $encryptedDataProto = new EncryptedDataProto();
        $encryptedDataProto->mergeFromString(base64_decode($data));
        return self::decryptFromProto($encryptedDataProto, $wrappedKey, $pem);
    }

    /**
     * @param \Yoti\Protobuf\Compubapi\EncryptedData $encryptedDataProto
     * @param string $wrappedKey
     * @param string $pem
     *
     * @return string
     */
    public static function decryptFromProto(EncryptedDataProto $encryptedDataProto, $wrappedKey, $pem)
    {
        openssl_private_decrypt(
            base64_decode($wrappedKey),
            $unwrappedKey,
            $pem
        );

        return openssl_decrypt(
            $encryptedDataProto->getCipherText(),
            'aes-256-cbc',
            $unwrappedKey,
            OPENSSL_RAW_DATA,
            $encryptedDataProto->getIv()
        );
    }
}
