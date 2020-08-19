<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Objective;

class ProofOfAddressObjective extends Objective
{
    private const TYPE = 'PROOF_OF_ADDRESS';

    public function __construct()
    {
        parent::__construct(self::TYPE);
    }
}
