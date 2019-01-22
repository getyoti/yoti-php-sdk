<?php

namespace Yoti\Http;

use Yoti\Exception\RequestException;

class RequestSigner
{
    const SIGNED_MESSAGE_KEY = 'signed_message';
    const END_POINT_PATH_KEY = 'end_point_path';

    /**
     * Return request signed data.
     *
     * @param AbstractRequestHandler $requestHandler
     * @param string $endpoint
     * @param string $httpMethod
     * @param Payload|NULL $payload
     *
     * @return array
     *
     * @throws RequestException
     */
    public static function signRequest(AbstractRequestHandler $requestHandler, $endpoint, $httpMethod, Payload $payload = NULL)
    {
        $endPointPath = self::generateEndPointPath($endpoint, $requestHandler->getSDKId());

        $messageToSign = "{$httpMethod}&$endPointPath";
        if ($payload instanceof Payload) {
            $messageToSign .= "&{$payload->getBase64Payload()}";
        }

        openssl_sign($messageToSign, $signedMessage, $requestHandler->getPem(), OPENSSL_ALGO_SHA256);

        self::validateSignedMessage($signedMessage);

        $base64SignedMessage = base64_encode($signedMessage);

        return [
            self::SIGNED_MESSAGE_KEY => $base64SignedMessage,
            self::END_POINT_PATH_KEY => $endPointPath
        ];
    }

    /**
     * @param string $endpoint
     * @param string $sdkId
     *
     * @return string
     */
    private static function generateEndPointPath($endpoint, $sdkId)
    {
        // Prepare message to sign
        $nonce = self::generateNonce();
        $timestamp = round(microtime(true) * 1000);

        return "{$endpoint}?nonce={$nonce}&timestamp={$timestamp}&appId={$sdkId}";
    }

    /**
     * @param string $signedMessage
     *
     * @throws RequestException
     */
    private static function validateSignedMessage($signedMessage)
    {
        // Check signed message
        if(!$signedMessage) {
            throw new RequestException('Could not sign request.', 401);
        }
    }

    /**
     * @return string
     */
    private static function generateNonce()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}