<?php

namespace Yoti\ShareUrl;

use Yoti\Http\Response;
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
     * @param \Yoti\Http\Response $response
     */
    public function __construct(Response $response)
    {
        $json = json_decode($response->getBody(), true);

        Validation::isString($json['qrcode'], 'QR Code URL');
        $this->shareUrl = $json['qrcode'];

        Validation::isString($json['ref_id'], 'Ref ID');
        $this->refId = $json['ref_id'];
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
