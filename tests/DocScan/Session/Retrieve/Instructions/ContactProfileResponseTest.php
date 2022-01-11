<?php

namespace Yoti\Test\DocScan\Session\Retrieve\Instructions;

use Yoti\DocScan\Session\Retrieve\Instructions\ContactProfileResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\Instructions\ContactProfileResponse
 */
class ContactProfileResponseTest extends TestCase
{
    private const SOME_FIRST_NAME = 'SOME_FIRST_NAME';
    private const SOME_LAST_NAME = 'SOME_LAST_NAME';
    private const SOME_EMAIL = 'SOME_EMAIL';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getFirstName
     * @covers ::getEmail
     * @covers ::getLastName
     */
    public function shouldBuildCorrectly(): void
    {
        $data = [
            'first_name' => self::SOME_FIRST_NAME,
            'last_name' => self::SOME_LAST_NAME,
            'email' => self::SOME_EMAIL,
        ];

        $result = new ContactProfileResponse($data);

        $this->assertEquals(self::SOME_FIRST_NAME, $result->getFirstName());
        $this->assertEquals(self::SOME_LAST_NAME, $result->getLastName());
        $this->assertEquals(self::SOME_EMAIL, $result->getEmail());
    }
}
