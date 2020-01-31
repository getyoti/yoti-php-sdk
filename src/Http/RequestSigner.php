<?php

declare(strict_types=1);

namespace Yoti\Http;

use Yoti\Http\Exception\RequestSignerException;
use Yoti\Util\PemFile;

class RequestSigner
{
    /**
     * Return request signed data.
     *
     * @param \Yoti\Util\PemFile $pemFile
     * @param string $endpoint
     * @param string $httpMethod
     * @param \Yoti\Http\Payload|NULL $payload
     *
     * @return string
     *   The base64 encoded signed message
     *
     * @throws \Yoti\Http\Exception\RequestSignerException
     */
    public static function sign(
        PemFile $pemFile,
        string $endpoint,
        string $httpMethod,
        Payload $payload = null
    ): string {
        $messageToSign = "{$httpMethod}&$endpoint";
        if ($payload instanceof Payload) {
            $messageToSign .= "&{$payload->toBase64()}";
        }

        openssl_sign($messageToSign, $signedMessage, (string) $pemFile, OPENSSL_ALGO_SHA256);

        if (!$signedMessage) {
            throw new RequestSignerException('Could not sign request.');
        }

        return base64_encode($signedMessage);
    }
}
