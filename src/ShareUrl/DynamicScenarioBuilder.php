<?php

declare(strict_types=1);

namespace Yoti\ShareUrl;

use Yoti\ShareUrl\Extension\Extension;
use Yoti\ShareUrl\Policy\DynamicPolicy;

/**
 * Builder for DynamicScenario.
 */
class DynamicScenarioBuilder
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
    private $extensions = [];

    /**
     * @param string $callbackEndpoint
     *
     * @return $this
     */
    public function withCallbackEndpoint(string $callbackEndpoint): self
    {
        $this->callbackEndpoint = $callbackEndpoint;
        return $this;
    }

    /**
     * @param \Yoti\ShareUrl\Policy\DynamicPolicy $dynamicPolicy
     *
     * @return $this
     */
    public function withPolicy(DynamicPolicy $dynamicPolicy): self
    {
        $this->dynamicPolicy = $dynamicPolicy;
        return $this;
    }

    /**
     * @param \Yoti\ShareUrl\Extension\Extension $extension
     *
     * @return $this
     */
    public function withExtension(Extension $extension): self
    {
        $this->extensions[] = $extension;
        return $this;
    }

    /**
     * @return \Yoti\ShareUrl\DynamicScenario
     */
    public function build(): DynamicScenario
    {
        return new DynamicScenario($this->callbackEndpoint, $this->dynamicPolicy, $this->extensions);
    }
}
