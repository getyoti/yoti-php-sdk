<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Create\Objective;

class ProofOfAddressObjectiveBuilder
{
    /**
     * @return ProofOfAddressObjective
     */
    public function build(): ProofOfAddressObjective
    {
        return new ProofOfAddressObjective();
    }
}
