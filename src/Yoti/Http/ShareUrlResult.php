<?php

namespace Yoti\Http;

use Yoti\Exception\ShareUrlException;
use Yoti\Util\Validation;

/**
 * The share result, containing the share URL and ref ID.
 */
class ShareUrlResult
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
     * @param array $result
     */
    public function __construct(array $result)
    {
        $this->shareUrl = $this->getResultValue($result, 'qrcode');
        $this->refId = $this->getResultValue($result, 'ref_id');
    }

    /**
     * @param array $result
     * @param string $key
     *
     * @return string
     *
     * @throws \Yoti\Exception\ShareUrlException
     */
    private function getResultValue(array $result, $key)
    {
        if (empty($result[$key])) {
            throw new ShareUrlException("JSON result does not contain '{$key}'");
        }
        Validation::isString($result[$key], $key);
        return $result[$key];
    }

    /**
     * The URL that the 3rd party should use for the share.
     *
     * @return string The share URL
     */
    public function getShareUrl()
    {
        return $this->shareUrl;
    }

    /**
     * Get the Yoti reference id for the share.
     *
     * @return string reference id for the share
     */
    public function getRefId()
    {
        return $this->refId;
    }
}
