<?php

declare(strict_types=1);

namespace Yoti\ShareUrl\Policy;

use Yoti\Util\Json;
use Yoti\Util\Validation;

/**
 * Defines the list of wanted attributes.
 */
class DynamicPolicy implements \JsonSerializable
{
    /**
     * @var \Yoti\ShareUrl\Policy\WantedAttribute[]
     */
    private $wantedAttributes;

    /**
     * @var int[]
     */
    private $wantedAuthTypes;

    /**
     * @var bool
     */
    private $wantedRememberMe;

    /**
     * @param \Yoti\ShareUrl\Policy\WantedAttribute[] $wantedAttributes
     *   Array of attributes to be requested.
     * @param int[] $wantedAuthTypes
     *   Auth types represents the authentication type to be used.
     * @param bool $wantedRememberMe
     */
    public function __construct(
        array $wantedAttributes,
        array $wantedAuthTypes,
        bool $wantedRememberMe = false
    ) {
        Validation::isArrayOfType($wantedAttributes, [WantedAttribute::class], 'wantedAttributes');
        $this->wantedAttributes = $wantedAttributes;

        Validation::isArrayOfIntegers($wantedAuthTypes, 'wantedAuthTypes');
        $this->wantedAuthTypes = $wantedAuthTypes;

        $this->wantedRememberMe = $wantedRememberMe;
    }

    /**
     * @inheritDoc
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'wanted' => $this->wantedAttributes,
            'wanted_auth_types' => $this->wantedAuthTypes,
            'wanted_remember_me' => $this->wantedRememberMe,
            'wanted_remember_me_optional' => false,
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return Json::encode($this);
    }
}
