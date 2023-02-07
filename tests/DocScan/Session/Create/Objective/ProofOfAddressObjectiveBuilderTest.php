<?php

namespace Yoti\Test\IDV\Session\Create\Check\Objective;

use Yoti\IDV\Session\Create\Objective\Objective;
use Yoti\IDV\Session\Create\Objective\ProofOfAddressObjective;
use Yoti\IDV\Session\Create\Objective\ProofOfAddressObjectiveBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Create\Objective\ProofOfAddressObjectiveBuilder
 */
class ProofOfAddressObjectiveBuilderTest extends TestCase
{
    private const PROOF_OF_ADDRESS = 'PROOF_OF_ADDRESS';

    /**
     * @test
     *
     * @covers ::build
     * @covers \Yoti\IDV\Session\Create\Objective\Objective::__construct
     * @covers \Yoti\IDV\Session\Create\Objective\Objective::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\Objective\ProofOfAddressObjective::__construct
     * @covers \Yoti\IDV\Session\Create\Objective\ProofOfAddressObjective::jsonSerialize
     */
    public function shouldBuildProofOfAddressObjective()
    {
        $proofOfAddress = (new ProofOfAddressObjectiveBuilder())->build();

        $this->assertInstanceOf(ProofOfAddressObjective::class, $proofOfAddress);
        $this->assertInstanceOf(Objective::class, $proofOfAddress);

        $this->assertJsonStringEqualsJsonString(
            json_encode(
                (object) [
                    'type' => self::PROOF_OF_ADDRESS,
                ]
            ),
            json_encode($proofOfAddress)
        );
    }
}
