<?php

namespace Yoti\Http;

use Yoti\Exception\RequestException;
use Yoti\Util\PemFile;

class RequestSigner
{
    const SIGNED_MESSAGE_KEY = 'signed_message';
    const END_POINT_PATH_KEY = 'end_point_path';

    /**
     * Return request signed data.
     *
     * @deprecated 3.0.0
     *
     * @param AbstractRequestHandler $requestHandler
     * @param string $endpoint
     * @param string $httpMethod
     * @param Payload|NULL $payload
     * @param array $queryParams
     *
     * @return array
     *
     * @throws RequestException
     */
    public static function signRequest(
        AbstractRequestHandler $requestHandler,
        $endpoint,
        $httpMethod,
        Payload $payload = null,
        array $queryParams = []
    ) {
        if (!is_null($requestHandler->getSDKId())) {
            $queryParams['appId'] = $requestHandler->getSDKId();
        }

        return self::sign(
            PemFile::fromString($requestHandler->getPem()),
            $endpoint,
            $httpMethod,
            $payload,
            $queryParams
        );
    }

    /**
     * Return request signed data.
     *
     * @param \Yoti\Util\PemFile $pemFile
     * @param string $endpoint
     * @param string $httpMethod
     * @param \Yoti\Http\Payload|NULL $payload
     * @param array $queryParams
     *
     * @return array
     *
     * @throws RequestException
     */
    public static function sign(
        PemFile $pemFile,
        $endpoint,
        $httpMethod,
        Payload $payload = null,
        array $queryParams = []
    ) {
        $endPointPath = self::generateEndPointPath($endpoint, $queryParams);

        $messageToSign = "{$httpMethod}&$endPointPath";
        if ($payload instanceof Payload) {
            $messageToSign .= "&{$payload->getBase64Payload()}";
        }

        openssl_sign($messageToSign, $signedMessage, (string) $pemFile, OPENSSL_ALGO_SHA256);

        self::validateSignedMessage($signedMessage);

        $base64SignedMessage = base64_encode($signedMessage);

        return [
            self::SIGNED_MESSAGE_KEY => $base64SignedMessage,
            self::END_POINT_PATH_KEY => $endPointPath
        ];
    }

    /**
     * @param string $endpoint
     * @param array $queryParams
     *
     * @return string
     */
    private static function generateEndPointPath($endpoint, array $queryParams = [])
    {
        // Prepare message to sign.
        return $endpoint . '?' . http_build_query(
            array_merge(
                $queryParams,
                [
                    'nonce' => self::generateNonce(),
                    'timestamp' => round(microtime(true) * 1000),
                ]
            )
        );
    }

    /**
     * @param string $signedMessage
     *
     * @throws RequestException
     */
    private static function validateSignedMessage($signedMessage)
    {
        // Check signed message
        if (!$signedMessage) {
            throw new RequestException('Could not sign request.', 401);
        }
    }

    /**
     * @return string
     */
    private static function generateNonce()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
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
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
