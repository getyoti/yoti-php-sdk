<?php

declare(strict_types=1);

namespace Yoti\ShareUrl;

use Yoti\ShareUrl\Extension\Extension;
use Yoti\ShareUrl\Policy\DynamicPolicy;
use Yoti\Util\Json;
use Yoti\Util\Validation;

/**
 * Defines the Dynamic Scenario callback endpoint, policy
 * and extensions.
 */
class DynamicScenario implements \JsonSerializable
{
    /**
     * @var string
     */
    private $callbackEndpoint;

    /**
     * @var \Yoti\ShareUrl\Policy\DynamicPolicy
     */
    private $dynamicPolicy;

    /**
     * @var \Yoti\ShareUrl\Extension\Extension[]
     */
    private $extensions;

    /**
     * @param string $callbackEndpoint
     *   The device's callback endpoint. Must be a URL relative to the Application
     *   Domain specified in your Yoti Hub.
     * @param \Yoti\ShareUrl\Policy\DynamicPolicy $dynamicPolicy
     *   The customisable DynamicPolicy to use in the share.
     * @param \Yoti\ShareUrl\Extension\Extension[] $extensions
     *   List of Extension to be activated for the application.
     */
    public function __construct(string $callbackEndpoint, DynamicPolicy $dynamicPolicy, array $extensions)
    {
        $this->callbackEndpoint = $callbackEndpoint;
        $this->dynamicPolicy = $dynamicPolicy;

        Validation::isArrayOfType($extensions, [Extension::class], 'extensions');
        $this->extensions = $extensions;
    }

    /**
     * @inheritDoc
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'callback_endpoint' => $this->callbackEndpoint,
            'policy' => $this->dynamicPolicy,
            'extensions' => $this->extensions,
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
