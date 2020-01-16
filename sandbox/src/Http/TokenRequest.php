<?php

declare(strict_types=1);

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

    public function __construct(?string $rememberMeId, array $sandboxAttrs)
    {
        $this->rememberMeId = $rememberMeId;
        $this->sandboxAttributes = $sandboxAttrs;
    }

    public function getRememberMeId(): string
    {
        return $this->rememberMeId;
    }

    public function getSandboxAttributes(): array
    {
        return $this->sandboxAttributes;
    }

    public function getPayload(): Payload
    {
        return Payload::fromJsonData([
            'remember_me_id' => $this->rememberMeId,
            'profile_attributes' => $this->sandboxAttributes,
        ]);
    }
}
