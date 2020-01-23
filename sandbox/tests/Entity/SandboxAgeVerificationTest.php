<?php

declare(strict_types=1);

namespace SandboxTest\Entity;

use Yoti\Profile\UserProfile;
use YotiSandbox\Entity\SandboxAgeVerification;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \YotiSandbox\Entity\SandboxAgeVerification
 */
class SandboxAgeVerificationTest extends TestCase
{
    /**
     * @var SandboxAgeVerification
     */
    public $ageVerification;

    public function setup(): void
    {
        $dateTime = (new \DateTime())->setTimestamp(1171502725);
        $this->ageVerification = new SandboxAgeVerification(
            $dateTime,
            'age_under:18'
        );
    }

    /**
     * @covers ::getName
     * @covers ::__construct
     */
    public function testGetName()
    {
        $this->assertEquals(
            UserProfile::ATTR_DATE_OF_BIRTH,
            $this->ageVerification->getName()
        );
    }

    /**
     * @covers ::getValue
     * @covers ::__construct
     */
    public function testGetValue()
    {
        $this->assertEquals('2007-02-15', $this->ageVerification->getValue());
    }

    /**
     * @covers ::getDerivation
     * @covers ::__construct
     */
    public function testGetDerivation()
    {
        $this->assertEquals('age_under:18', $this->ageVerification->getDerivation());
    }

    /**
     * @covers ::getOptional
     * @covers ::__construct
     */
    public function testGetOptional()
    {
        $this->assertEquals('true', $this->ageVerification->getOptional());
    }

    /**
     * @covers ::getAnchors
     * @covers ::__construct
     */
    public function testGetAnchors()
    {
        $this->assertEquals(
            json_encode([]),
            json_encode($this->ageVerification->getAnchors())
        );
    }

    /**
     * @covers ::getDerivation
     * @covers ::setAgeOver
     * @covers ::__construct
     */
    public function testGetAgeOver()
    {
        $ageVerification = clone $this->ageVerification;
        $ageVerification->setAgeOver(20);
        $this->assertEquals('age_over:20', $ageVerification->getDerivation());
    }

    /**
     * @covers ::getDerivation
     * @covers ::setAgeUnder
     * @covers ::__construct
     */
    public function testAgeUnder()
    {
        $ageVerification = clone $this->ageVerification;
        $ageVerification->setAgeUnder(18);
        $this->assertEquals('age_under:18', $ageVerification->getDerivation());
    }
}
