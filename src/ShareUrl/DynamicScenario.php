<?php

declare(strict_types=1);

namespace Yoti\ShareUrl;

use stdClass;
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
     * @var object|null
     */
    private $subject;

    /**
     * @param string $callbackEndpoint
     *   The device's callback endpoint. Must be a URL relative to the Application
     *   Domain specified in your Yoti Hub.
     * @param \Yoti\ShareUrl\Policy\DynamicPolicy $dynamicPolicy
     *   The customisable DynamicPolicy to use in the share.
     * @param \Yoti\ShareUrl\Extension\Extension[] $extensions
     *   List of Extension to be activated for the application.
     * @param object $subject
     *   Set of data required to represent an identity profile
     */
    public function __construct(
        string $callbackEndpoint,
        DynamicPolicy $dynamicPolicy,
        array $extensions,
        $subject = null
    ) {
        $this->callbackEndpoint = $callbackEndpoint;
        $this->dynamicPolicy = $dynamicPolicy;

        Validation::isArrayOfType($extensions, [Extension::class], 'extensions');
        $this->extensions = $extensions;
        $this->subject = $subject;
    }

    /**
     * @inheritDoc
     *
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        return (object)[
            'callback_endpoint' => $this->callbackEndpoint,
            'policy' => $this->dynamicPolicy,
            'extensions' => $this->extensions,
            'subject' => $this->subject,
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return Json::encode($this);
    }

    /**
     * @return object|null
     */
    public function getSubject()
    {
        return $this->subject;
    }
}
