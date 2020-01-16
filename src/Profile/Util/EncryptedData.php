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
     * @param \Yoti\Util\PemFile $pemFile
     *
     * @return string
     */
    public static function decrypt(string $data, string $wrappedKey, PemFile $pemFile): string
    {
        $decodedProto = base64_decode($data, true);
        if ($decodedProto === false) {
            throw new EncryptedDataException('Could not decode data');
        }

        $encryptedDataProto = new EncryptedDataProto();
        $encryptedDataProto->mergeFromString($decodedProto);

        $decodedWrappedKey = base64_decode($wrappedKey, true);
        if ($decodedWrappedKey === false) {
            throw new EncryptedDataException('Could not decode wrapped key');
        }

        openssl_private_decrypt(
            $decodedWrappedKey,
            $unwrappedKey,
            (string) $pemFile
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
