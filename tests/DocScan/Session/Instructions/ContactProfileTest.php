<?php

namespace Yoti\Test\IDV\Session\Instructions;

use Yoti\IDV\Session\Instructions\ContactProfileBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Instructions\ContactProfile
 */
class ContactProfileTest extends TestCase
{
    private const SOME_FIRST_NAME = "someFirstName";
    private const SOME_LAST_NAME = "someLastName";
    private const SOME_EMAIL_ADDRESS = "someEmailAddress";

    /**
     * @test
     * @covers ::__construct
     * @covers ::getLastName
     * @covers ::getEmail
     * @covers ::getFirstName
     * @covers \Yoti\IDV\Session\Instructions\ContactProfileBuilder::build
     * @covers \Yoti\IDV\Session\Instructions\ContactProfileBuilder::withFirstName
     * @covers \Yoti\IDV\Session\Instructions\ContactProfileBuilder::withEmail
     * @covers \Yoti\IDV\Session\Instructions\ContactProfileBuilder::withLastName
     */
    public function builderShouldBuildWithAllProperties()
    {
        $result = (new ContactProfileBuilder())
            ->withFirstName(self::SOME_FIRST_NAME)
            ->withEmail(self::SOME_EMAIL_ADDRESS)
            ->withLastName(self::SOME_LAST_NAME)
            ->build();

        $this->assertEquals(self::SOME_FIRST_NAME, $result->getFirstName());
        $this->assertEquals(self::SOME_LAST_NAME, $result->getLastName());
        $this->assertEquals(self::SOME_EMAIL_ADDRESS, $result->getEmail());
    }
}
