<?php

declare(strict_types=1);

namespace Yoti\Profile\Util;

use Yoti\Exception\EncryptedDataException;
use Yoti\Protobuf\Compubapi\EncryptedData as EncryptedDataProto;
use Yoti\Util\PemFile;

class EncryptedData
{
    /**
     * @param string $data
     * @param string $wrappedKey
     * @param Yoti\Util\PemFile $pem
     *
     * @return string
     */
    public static function decrypt(string $data, string $wrappedKey, PemFile $pem): string
    {
        $encryptedDataProto = new EncryptedDataProto();
        $encryptedDataProto->mergeFromString(base64_decode($data));

        openssl_private_decrypt(
            base64_decode($wrappedKey),
            $unwrappedKey,
            $pem
        );

        $decrypted = openssl_decrypt(
            $encryptedDataProto->getCipherText(),
            'aes-256-cbc',
            $unwrappedKey,
            OPENSSL_RAW_DATA,
            $encryptedDataProto->getIv()
        );

        if ($decrypted !== false) {
            return $decrypted;
        }

        throw new EncryptedDataException('Could not decrypt data');
    }
}
