<?php

declare(strict_types=1);

namespace SandboxTest\Entity;

use YotiSandbox\Entity\SandboxDocumentDetails;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \YotiSandbox\Entity\SandboxDocumentDetails
 */
class SandboxDocumentDetailsTest extends TestCase
{
    /**
     * @covers ::getValue
     *
     * @dataProvider documentDetailsDataProvider
     */
    public function testGetValue($someDocumentDetailsString)
    {
        $sandboxDocumentDetails = new SandboxDocumentDetails($someDocumentDetailsString);

        $this->assertEquals(
            $someDocumentDetailsString,
            $sandboxDocumentDetails->getValue()
        );
    }

    /**
     * Provides valid document details string values.
     */
    public function documentDetailsDataProvider()
    {
        return [
            ['PASSPORT GBR 01234567 2020-01-01 some_authority'],
            ['PASSPORT GBR 01234567 2020-01-01'],
            ['PASSPORT GBR 01234567 - some_authority'],
            ['PASSPORT GBR 01234567 -'],
        ];
    }
}
