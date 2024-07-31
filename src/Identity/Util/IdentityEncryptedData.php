<?php

declare(strict_types=1);

namespace Yoti\Identity\Util;

use Yoti\Exception\EncryptedDataException;
use Yoti\Protobuf\Compubapi\EncryptedData as EncryptedDataProto;

class IdentityEncryptedData
{
    /**
     * @param string $data
     * @param string $unwrappedKey
     *
     * @return string
     */
    public static function decrypt(string $data, string $unwrappedKey): string
    {
        if ($data === "") {
            throw new EncryptedDataException('Could not decode data');
        }

        $encryptedDataProto = new EncryptedDataProto();
        $encryptedDataProto->mergeFromString($data);

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
