<?php

namespace Yoti\Http;

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
        Validation::isString($result['qrcode'], 'QR Code URL');
        $this->shareUrl = $result['qrcode'];

        Validation::isString($result['ref_id'], 'Ref ID');
        $this->refId = $result['ref_id'];
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
