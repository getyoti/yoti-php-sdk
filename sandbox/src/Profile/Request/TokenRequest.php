<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Profile\Request;

use Yoti\Http\Payload;
use Yoti\Sandbox\Profile\Request\Attribute\SandboxAttribute;
use Yoti\Util\Validation;

class TokenRequest implements \JsonSerializable
{
    /**
     * @var string|null
     */
    private $rememberMeId;

    /**
     * @var SandboxAttribute[]
     */
    private $sandboxAttributes;

    /**
     * @param string|null $rememberMeId
     * @param SandboxAttribute[] $sandboxAttributes
     */
    public function __construct(?string $rememberMeId, array $sandboxAttributes)
    {
        $this->rememberMeId = $rememberMeId;

        Validation::isArrayOfType($sandboxAttributes, [ SandboxAttribute::class ], 'sandboxAttributes');
        $this->sandboxAttributes = $sandboxAttributes;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'remember_me_id' => $this->rememberMeId,
            'profile_attributes' => $this->sandboxAttributes,
        ];
    }

    /**
     * @return Payload
     */
    public function getPayload(): Payload
    {
        return Payload::fromJsonData($this);
    }
}
