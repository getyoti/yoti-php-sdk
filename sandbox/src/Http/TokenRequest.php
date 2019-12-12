<?php

namespace YotiSandbox\Http;

use Yoti\Http\Payload;

class TokenRequest
{
    /**
     * @var string
     */
    private $rememberMeId;

    /**
     * @var array
     */
    private $sandboxAttributes;

    public function __construct($rememberMeId, array $sandboxAttrs)
    {
        $this->rememberMeId = $rememberMeId;
        $this->sandboxAttributes = $sandboxAttrs;
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
        return Payload::fromJsonData([
            'remember_me_id' => $this->rememberMeId,
            'profile_attributes' => $this->sandboxAttributes,
        ]);
    }
}
