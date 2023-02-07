<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Create\Check\Advanced;

class RequestedFuzzyMatchingStrategyBuilder
{
    /**
     * @var float
     */
    private $fuzziness;

    /**
     * @param float $fuzziness
     * @return $this
     */
    public function withFuzziness(float $fuzziness): RequestedFuzzyMatchingStrategyBuilder
    {
        $this->fuzziness = $fuzziness;

        return $this;
    }

    /**
     * @return RequestedFuzzyMatchingStrategy
     */
    public function build(): RequestedFuzzyMatchingStrategy
    {
        return new RequestedFuzzyMatchingStrategy($this->fuzziness);
    }
}
