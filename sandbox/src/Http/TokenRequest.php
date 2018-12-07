<?php

namespace YotiSandbox\Http;

use Yoti\Http\Payload;

class TokenRequest
{
    private $rememberMeId;
    private $sandboxAttributes;

    public function __construct($rememberMeId, array $sandboxAttrs)
    {
        $this->rememberMeId = $rememberMeId;
        $this->sandboxAttributes['profile_attributes'] = $sandboxAttrs;
    }

    public function getRememberMeId()
    {
        return $this->rememberMeId;
    }

    public function getSandboxAttributes()
    {
        return $this->sandboxAttributes;
    }

    public function getPayload()
    {
        $payloadData = [
            'remember_me_id' => $this->rememberMeId,
            $this->sandboxAttributes
        ];
        return new Payload($payloadData);
    }
}