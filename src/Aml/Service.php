<?php

declare(strict_types=1);

namespace Yoti\Aml;

use Psr\Http\Message\ResponseInterface;
use Yoti\Constants;
use Yoti\Exception\AmlException;
use Yoti\Http\Payload;
use Yoti\Http\RequestBuilder;
use Yoti\Util\Config;
use Yoti\Util\Json;
use Yoti\Util\PemFile;

class Service
{
    /**
     * @var string
     */
    private $sdkId;

    /**
     * @var \Yoti\Util\PemFile
     */
    private $pemFile;

    /**
     * @var \Yoti\Util\Config
     */
    private $config;

    /**
     * @param string $sdkId
     * @param \Yoti\Util\PemFile $pemFile
     * @param \Yoti\Util\Config $config
     */
    public function __construct(string $sdkId, PemFile $pemFile, Config $config)
    {
        $this->sdkId = $sdkId;
        $this->pemFile = $pemFile;
        $this->config = $config;
    }

    /**
     * @param \Yoti\Aml\Profile $amlProfile
     *
     * @return \Yoti\Aml\Result
     *
     * @throws \Yoti\Exception\AmlException
     */
    public function performCheck(Profile $amlProfile): Result
    {
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getApiUrl() ?? Constants::API_URL)
            ->withEndpoint('/aml-check')
            ->withQueryParam('appId', $this->sdkId)
            ->withPost()
            ->withPayload(Payload::fromJsonData($amlProfile))
            ->withPemFile($this->pemFile)
            ->build()
            ->execute();

        // Validate result
        $this->validateAmlResult($response);

        // Set and return result
        return new Result(Json::decode((string) $response->getBody()));
    }

    /**
     * Handle request result.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
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

        $responseArr = Json::decode((string) $response->getBody());

        $errorMessage = $this->getErrorMessage($responseArr);
        $errorCode = isset($responseArr['code']) ? $responseArr['code'] : 'Error';

        // Throw the error message that's included in the response
        if (strlen($errorMessage) > 0) {
            throw new AmlException("$errorCode - {$errorMessage}");
        }

        // Throw a general error message
        throw new AmlException("{$errorCode} - Server responded with {$httpCode}");
    }

    /**
     * Get error message from the response array.
     *
     * @param array<string, array> $result
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
