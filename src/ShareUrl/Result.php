<?php

declare(strict_types=1);

namespace Yoti\ShareUrl;

use Psr\Http\Message\ResponseInterface;
use Yoti\Exception\ShareUrlException;
use Yoti\Util\Validation;

/**
 * The share result, containing the share URL and ref ID.
 */
class Result
{
    /**
     * @var string
     */
    private $shareUrl;

    /**
     * @var string
     */
    private $refId;

    /**
     * @param array<string, string> $result
     * @throws ShareUrlException
     */
    public function __construct(array $result, ResponseInterface $response)
    {
        $this->shareUrl = $this->getResultValue($result, 'qrcode', $response);
        $this->refId = $this->getResultValue($result, 'ref_id', $response);
    }

    /**
     * @param array<string, string> $result
     * @param string $key
     * @param ResponseInterface $response
     * @return string
     *
     * @throws ShareUrlException
     */
    private function getResultValue(array $result, string $key, ResponseInterface $response): string
    {
        if (!isset($result[$key])) {
            throw new ShareUrlException("JSON result does not contain '{$key}'", $response);
        }
        Validation::isString($result[$key], $key);
        return $result[$key];
    }

    /**
     * The URL that the 3rd party should use for the share.
     *
     * @return string The share URL
     */
    public function getShareUrl(): string
    {
        return $this->shareUrl;
    }

    /**
     * Get the Yoti reference id for the share.
     *
     * @return string reference id for the share
     */
    public function getRefId(): string
    {
        return $this->refId;
    }
}
