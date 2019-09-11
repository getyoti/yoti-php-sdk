<?php

namespace YotiTest\ShareUrl\Policy;

use Yoti\ShareUrl\Policy\Constraints;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Policy\Constraints
 */
class ConstraintsTest extends TestCase
{
    /**
     * @covers ::__construct
     *
     * @expectedException TypeError
     * @expectedExceptionMessage constraints must be array of Yoti\ShareUrl\Policy\SourceConstraint
     */
    public function testInvalidConstraint()
    {
        new Constraints(['invalid type']);
    }
}
