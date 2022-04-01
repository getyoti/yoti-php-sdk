<?php

namespace Yoti\Test\DocScan\Session\Create\Check\Objective;

use Yoti\DocScan\Session\Create\Objective\Objective;
use Yoti\DocScan\Session\Create\Objective\ProofOfAddressObjective;
use Yoti\DocScan\Session\Create\Objective\ProofOfAddressObjectiveBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Objective\ProofOfAddressObjectiveBuilder
 */
class ProofOfAddressObjectiveBuilderTest extends TestCase
{
    private const PROOF_OF_ADDRESS = 'PROOF_OF_ADDRESS';

    /**
     * @test
     *
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Objective\Objective::__construct
     * @covers \Yoti\DocScan\Session\Create\Objective\Objective::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Objective\ProofOfAddressObjective::__construct
     * @covers \Yoti\DocScan\Session\Create\Objective\ProofOfAddressObjective::jsonSerialize
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
