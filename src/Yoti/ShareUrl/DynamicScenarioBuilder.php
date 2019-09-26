<?php

namespace Yoti\ShareUrl;

use Yoti\ShareUrl\Policy\DynamicPolicy;
use Yoti\ShareUrl\Extension\Extension;

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
     * @return \Yoti\ShareUrl\DynamicScenarioBuilder
     */
    public function withCallbackEndpoint($callbackEndpoint)
    {
        $this->callbackEndpoint = $callbackEndpoint;
        return $this;
    }

    /**
     * @param \Yoti\ShareUrl\Policy\DynamicPolicy $dynamicPolicy
     *
     * @return \Yoti\ShareUrl\DynamicScenarioBuilder
     */
    public function withPolicy(DynamicPolicy $dynamicPolicy)
    {
        $this->dynamicPolicy = $dynamicPolicy;
        return $this;
    }

    /**
     * @param \Yoti\ShareUrl\Extension\Extension $extension
     *
     * @return \Yoti\ShareUrl\DynamicScenarioBuilder
     */
    public function withExtension(Extension $extension)
    {
        $this->extensions[] = $extension;
        return $this;
    }

    /**
     * @return \Yoti\ShareUrl\DynamicScenario
     */
    public function build()
    {
        return new DynamicScenario($this->callbackEndpoint, $this->dynamicPolicy, $this->extensions);
    }
}
