<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Profile\Request;

use Yoti\Http\Payload;

class TokenRequest
{
    /**
     * @var string|null
     */
    private $rememberMeId;

    /**
     * @var array[]
     */
    private $sandboxAttributes;

    /**
     * @param string|null $rememberMeId
     * @param array[] $sandboxAttrs
     */
    public function __construct(?string $rememberMeId, array $sandboxAttrs)
    {
        $this->rememberMeId = $rememberMeId;
        $this->sandboxAttributes = $sandboxAttrs;
    }

    public function getRememberMeId(): ?string
    {
        return $this->rememberMeId;
    }

    /**
     * @return array[]
     */
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
