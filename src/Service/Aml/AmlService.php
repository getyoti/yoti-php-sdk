<?php

namespace Yoti\Service\Aml;

use Psr\Http\Message\ResponseInterface;
use Yoti\Entity\AmlProfile;
use Yoti\Exception\AmlException;
use Yoti\Http\Payload;
use Yoti\Http\RequestBuilder;
use Yoti\Util\Config;
use Yoti\Util\Json;
use Yoti\Util\PemFile;

class AmlService
{
    /**
     * @var \Yoti\Util\Config
     */
    private $config;

    /**
     * @param \Yoti\Util\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Yoti\Entity\AmlProfile $amlProfile
     *
     * @return \Yoti\Service\Aml\AmlResult
     *
     * @throws \Yoti\Exception\AmlException
     */
    public function performCheck(AmlProfile $amlProfile, PemFile $pemFile, string $sdkId): AmlResult
    {
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getConnectApiUrl())
            ->withEndpoint('/aml-check')
            ->withQueryParam('appId', $sdkId)
            ->withPost()
            ->withPayload(Payload::fromJsonData($amlProfile))
            ->withPemFile($pemFile)
            ->build()
            ->execute();

        // Validate result
        $this->validateAmlResult($response);

        // Set and return result
        return new AmlResult(Json::decode($response->getBody()));
    }

    /**
     * Handle request result.
     *
     * @param array $responseArr
     * @param int $httpCode
     *
     * @throws \Yoti\Exception\AmlException
     */
    private function validateAmlResult(ResponseInterface $response): void
    {
        $httpCode = $response->getStatusCode();

        if ($httpCode >= 200 && $httpCode < 300) {
            // The request is successful - nothing to do
            return;
        }

        $responseArr = Json::decode($response->getBody());

        $errorMessage = $this->getErrorMessage($responseArr);
        $errorCode = isset($responseArr['code']) ? $responseArr['code'] : 'Error';

        // Throw the error message that's included in the response
        if (!empty($errorMessage)) {
            throw new AmlException("$errorCode - {$errorMessage}");
        }

        // Throw a general error message
        throw new AmlException("{$errorCode} - Server responded with {$httpCode}");
    }

    /**
     * Get error message from the response array.
     *
     * @param array $result
     *
     * @return string
     */
    private function getErrorMessage(array $result): string
    {
        $errorMessage = '';
        if (isset($result['errors'][0]['property']) && isset($result['errors'][0]['message'])) {
            $errorMessage = $result['errors'][0]['property'] . ': ' . $result['errors'][0]['message'];
        }
        return $errorMessage;
    }
}
